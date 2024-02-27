<?php


namespace App\Services;


use App\Models\Curriculum\Repository\CurriculumRepositoryInterface;
use App\Models\Step\Repository\StepRepositoryInterface;
use App\Models\Progress\Repository\ProgressRepositoryInterface;
use App\Models\Student\Repository\StudentRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Curriculum\Curriculum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class CurriculumService extends AbstractService
{
    private CurriculumRepositoryInterface $curriculum;
    private StudentRepositoryInterface $student;
    private StepRepositoryInterface $step;
    private ProgressRepositoryInterface $progress;

    const STEP_IMAGE_PATH = '/step/image';
    const CURRICULUM_ZIP_PATH = '/curriculum/zip';

    public function __construct(
        CurriculumRepositoryInterface $curriculum,
        StudentRepositoryInterface $student,
        StepRepositoryInterface $step,
        ProgressRepositoryInterface $progress
    )
    {
        $this->curriculum = $curriculum;
        $this->student = $student;
        $this->step = $step;
        $this->progress = $progress;
    }

    /**
     * @param int|null $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        return $this->curriculum->getList($company_id);
    }

    /**
     * 詳細表示
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getById(int $id): ?array
    {
        $ret = $this->curriculum->find($id);
        return $ret !== null ? $ret->toArray() : null;
    }

    public function getDetail(int $id): ?array
    {
        $curriculum = $this->curriculum->find($id);
        if ($curriculum === null) {
            return null;
        }
        $steps = $this->step->getListByCurriculumId($id);
        if (count($steps) > 0) {
            $steps = $this->formatSteps($steps);
        }
        return [
            'curriculum' => $curriculum,
            'steps' => $steps,
        ];
    }

    /**
     * @param $request
     * @return bool
     */
    public function save(Request $request, $id = null): bool
    {
        try {
            DB::beginTransaction();
            try {
                //カリキュラムデータの登録
                $curriculum = $request->curriculum;
                if (isset($curriculum['zip']) && $curriculum['zip'] instanceof UploadedFile) {
                    $curriculum['zip_name'] = $curriculum['zip']->getClientOriginalName();
                    $curriculum['zip'] = $this->saveZipAndGetPath($curriculum['zip']);
                }
                $curriculum = $this->curriculum->save($this->curriculum->getModelAndSetParams($curriculum, $id));
            } catch (Exception $e) {
                Log::error($e->getMessage());
                throw new \Exception('カリキュラム情報の登録に失敗しました');
            }
            try {
                // ステップの削除
                $deleted = $request->deleted;
                if ($deleted !== null) {
                    foreach ($deleted as $step_id) {
                        $this->step->delete($step_id);
                    }
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                throw new \Exception("ステップの削除に失敗しました。");
            }
            $steps = $request->steps;
            if ($steps === null) {
                // ステップなし
                DB::commit();
                return true;
            }
            try {
                // ステップの更新
                $num = 1;
                foreach ($steps as $key => $step) {
                    $num++;
                    if (isset($step['image']) && $step['image'] instanceof UploadedFile) {
                        $step['image'] = $this->saveImageAndGetPath($step['image']);
                    }
                    $this->step->save($this->step->getModelAndSetParams($step, $curriculum->id, $key));
                }
                DB::commit();
                return true;
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                throw new \Exception("{$num}番目のステップ登録に失敗しました。");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param UploadedFile $zip
     * @return string|null
     */
    private function saveZipAndGetPath(UploadedFile $zip): ?string
    {
        $zip_name = Storage::disk('public')->putFile('/zips', $zip);
        return Storage::disk('public')->url('') . '/' . $zip_name;
    }

    /**
     * @param UploadedFile $image
     * @return string|null
     */
    private function saveImageAndGetPath(UploadedFile $image): ?string
    {
        $image_name = Storage::disk('public')->putFile('/images', $image);
        return Storage::disk('public')->url('') . '/' . $image_name;
    }

    /**
     * @param array $steps
     * @return array
     */
    private function formatSteps(array $steps): array
    {
        return $steps;
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return Curriculum
     */
    public function getModelAndSetParams(Request $request, int $id = null): Curriculum
    {
        $curriculum = new Curriculum();
        if ($id !== null) {
            $curriculum = $this->curriculum->find($id);
        }
        $curriculum->name = $request->name;
        $curriculum->course_id = $request->course_id;
        return $curriculum;
    }

    /**
     * @return array
     */
    public function getAutocomplete()
    {
        return $this->curriculum->getAutocompleteItem();
    }

    public function getAutocompleteWithCompanyId($company_id)
    {
        return $this->curriculum->getAutocompleteItemWithCompanyId($company_id);
    }

    public function getProgress(int $student_id): array
    {
        // コースに紐づくカリキュラムの一覧を取得
        $curriculums = $this->getCurriculumsFromStudentId($student_id);
        $result = [
            'curriculums' => $curriculums->toArray(),
            'status' => [],
            'average' => [],
            'target' => [],
            'deadline' => [],

        ];
        foreach ($curriculums as $curriculum) {
            /* @var $curriculum Curriculum */
            $result['status'][] = $this->progress->getCurriculumDateCount($student_id, $curriculum->id);
            $result['average'][] = $this->progress->getCurriculumAverage($curriculum->id);
            $result['target'][] = $this->curriculum->getCurriculumTarget($curriculum->id);
            $result['deadline'][] = $this->curriculum->getCurriculumDeadline($curriculum->id);
        }
        return $result;
    }


    public function getCurriculumsFromStudentId(int $student_id, bool $paginate = false): ?\Illuminate\Database\Eloquent\Collection
    {
        $student = $this->student->find($student_id);
        if ($student === null) {
            throw new Exception('生徒情報の取得に失敗しました');
        }
        if ($student->course_id === null) {
            return null;
        }
        // コースに紐づくカリキュラムの一覧を取得
        return $this->curriculum->getListByCourseId($student->course_id, $paginate);
    }

    public function getCurrentStep(int $curriculum_id, int $student_id)
    {
        $steps = $this->step->getStepsAndLatestProgressByCurriculumIdAndStudentId($curriculum_id, $student_id);
        if ($steps->count() === 0) {
            // ステップが登録されていない
            return false;
        }
        foreach ($steps as &$step) {
            if ($step->progress_status !== 3) {
                // 完了していないステップがある
                $step['progress_status'] = $step['progress_status'] ? config('const.PROGRESS_STATUS')["{$step['progress_status']}"] : null;
                return $step;
            }

            // if($step->percent !== 100){
            //     // 100%に到達していないステップがある
            //     return $step;
            // }
        }
        return false;
    }

    public function getCurrentCurriculumAndStepByStudentId($student_id): ?array
    {
        $curriculums = $this->getCurriculumsFromStudentId($student_id);
        if ($curriculums === null) {
            return null;
        }
        foreach ($curriculums as $curriculum) {
            /* @var Curriculum $curriculum */
            $step = $this->getCurrentStep($curriculum->id, $student_id);
            if (!$step) {
                // すべてのステップがクリア済み
                continue;
            }
            $result = [
                'curriculum' => $curriculum->toArray(),
                'step' => $step->toArray(),
            ];
            // ステップ単位の平均日数を取得
            $result['step']['average_count'] = $this->progress->getStepAverage($step->id);
            return $result;
        }
        return null;
    }

    public function getStudentProgressHistory(int $course_id, int $student_id): array
    {
        $curriculums = $this->curriculum->getListByCourseId($course_id);
        $result = $curriculums->toArray();
        foreach ($curriculums as $key => $curriculum) {
            $steps = $this->getStepHistory($curriculum->id, $student_id);
            $result[$key]['steps'] = $steps['steps'];
            $result[$key]['date_count'] = $this->progress->getCurriculumDateCount($student_id, $curriculum->id);
            $result[$key]['percent'] = round($steps['total_percent'], 1);
        }
        return [
            'data' => $result,
            'total' => count($result)
        ];
    }

    public function getStepHistory(int $curriculum_id, int $student_id): array
    {
        $steps = $this->step->getStepsDateCountByCurriculumAndStudent($curriculum_id, $student_id)->toArray();
        $total_date = 0;
        $percent_sum = 0;
        $latest_date = null;
        foreach ($steps as &$step) {
            $total_date += $step['date_count'];
            // 完了しているステップの進捗度を100に
            if ($step['latest_progress_status'] === 3) {
                $percent_sum += 100;
            }
            $step['latest_progress_status'] = $step['latest_progress_status'] ? config('const.PROGRESS_STATUS')["{$step['latest_progress_status']}"] : null;

            $latest_date = $step['latest_date'];
        }
        $total_percent = count($steps) ? $percent_sum / count($steps) : 0;
        return [
            'steps' => $steps,
            'total_date' => $total_date,
            'total_percent' => $total_percent,
            'latest_date' => $latest_date,
        ];
    }

    public function getCurriculumsAndProgressesFromStudentId(int $student_id, bool $paginate = false)
    {
        $student = $this->student->find($student_id);
        if ($student === null) {
            throw new Exception('生徒情報の取得に失敗しました');
        }
        $curriculums = $this->curriculum->getListByCourseId($student->course_id, $paginate);

        foreach ($curriculums as $key => $curriculum) {
            /** @var Curriculum $curriculum */
            $progress = $this->getStepHistory($curriculum->id, $student_id);
            $curriculum->setAttribute('steps', $progress['steps']);
            $curriculum->setAttribute('total_date', $progress['total_date']);
            $curriculum->setAttribute('total_percent', round($progress['total_percent'], 1));
            $curriculum->setAttribute('latest_date', $progress['latest_date']);
        }
        return $curriculums->toArray();
    }

}
