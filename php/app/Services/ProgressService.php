<?php


namespace App\Services;


use App\Models\Progress\Progress;
use App\Models\Progress\Repository\ProgressRepositoryInterface;
use App\Models\Report\Repository\ReportRepository;
use App\Models\Report\Repository\ReportRepositoryInterface;
use App\Models\Reservation\Repository\ReservationRepositoryInterface;
use App\Models\Step\Repository\StepRepositoryInterface;
use App\Models\Student\Repository\StudentRepositoryInterface;
use App\Models\Student\Student;
use App\Models\User\Repository\UserRepositoryInterface;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProgressService extends AbstractService
{
    private StudentRepositoryInterface $student;
    private ReservationRepositoryInterface $reservation;
    private ProgressRepositoryInterface $progress;
    private ReportRepositoryInterface $reports;
    private StepRepositoryInterface $step;

    public function __construct(
        StudentRepositoryInterface $student,
        ReservationRepositoryInterface $reservation,
        ProgressRepositoryInterface $progress,
        ReportRepositoryInterface $reports,
        StepRepositoryInterface $step
    )
    {
        $this->student = $student;
        $this->reservation = $reservation;
        $this->progress = $progress;
        $this->reports = $reports;
        $this->step = $step;
    }

    public function getList(?int $company_id): array
    {
        // TODO: Implement getList() method.
    }

    public function getById(int $id): ?array
    {
        // TODO: Implement getById() method.
    }

    /**
     * @param $company_id
     * @return array
     * @throws \Exception
     */
    public function getUserProgresses(?int $company_id = null): array
    {
        try {
            $result = [];
            $students = $this->student->getStudentsAndTodayReservations($company_id);
            foreach ($students as $student) {
                if (!$this->checkCurriculum($student)) {
                    // カリキュラムID絞り込み時、一致しなければ
                    continue;
                }
                /* @var $student Student */
                $item = $student->toArray();
                $item['default_attendance'] = false;
                if ($item['attendance_flg']) {
                    $item['default_attendance'] = true;
                }

                $item['attendance_count'] = $this->reservation->getTotalStudentReserveCount($student->id);
                $item['reservations'] = $this->reservation->getStudentReservations($student->id)->toArray();
                foreach ($item['reservations'] as $key => $reservation) {
                    $item['reservations'][$key]['reports'] = $this->reports->getReportsByReservations($reservation['id'])->toArray();
                    $item['reservations'][$key]['progresses'] = $this->progress->getProgressesByReservations($reservation['id'], false, $student->course_id)->toArray();
                }
                $result[] = $item;
            }
            return $result;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @param $company_id
     * @return bool
     * @throws \Exception
     */
    public function saveProgress(?int $company_id = null): bool
    {
        try {
            DB::beginTransaction();
            $data = request()->toArray();
            $this->updateReservation($data);
            $this->deleteProgress($data['student_id'], $data['progress'], $data['date']);
            foreach ($data['progress'] as $progress) {
                // 進捗データの保存
                $this->save($data['student_id'], $data['reservation_id'], $data['date'], $progress, config('const.APPLICATION_FLG_OFF'));
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }
    }

    private function save(int $student_id, int $reservation_id, string $date, array $data, int $application_flg)
    {
        $progress = new Progress();
        if (isset($data['id']) && !empty($data['id'])) {
            $progress = $this->progress->find($data['id']);
        }
        $progress->student_id = $student_id;
        $progress->date = $date;
        $progress->reservation_id = $reservation_id;
        $progress->step_id = $data['step_id'];
        $progress->progress_status = $data['progress_status'];
        $progress->application_flg = $application_flg;
        $this->progress->save($progress);
    }

    public function saveStudentProgress($student_id)
    {

        try {
            DB::beginTransaction();
            $data = request()->toArray();
            $this->save($student_id, $data['reservation_id'], date("Y-m-d"), $data, config('const.APPLICATION_FLG_OFF'));
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function getPrgoressedStep($student_id)
    {
        $datas = [];

        // 本日完了しているステップを取得
        $progresses = $this->progress->getPrgoressed($student_id)->toArray();

        if ($progresses) {
            foreach ($progresses as $progress) {
                $datas[] = array(
                    'id' => $progress['id'],
                    'text' => $progress['name'],
                    'step_id' => $progress['step_id'],
                    'progress_status' => $progress['progress_status']
                );
            }
        }

        $res = $this->getInProgressStep($student_id);

        if ($res) {
            $datas[] = $res;
        }

        return $datas;
    }


    public function getInProgressStep($student_id)
    {
        // 進捗テーブルに本日進行中のステップがある場合取得
        $progress = $this->progress->getInProgress($student_id);

        if (isset($progress['step_id'])) {
            return [];
        }

        // 最後にクリアしているステップIDを取得
        $cleared_progress = $this->progress->getClearedStepWithStudentId($student_id);

        // 生徒のステップ一覧を取得
        $steps = $this->step->getStepList($student_id)->toArray();

        foreach ($steps as $key => $step) {
            // ステップをクリアしていない場合最初のステップID
            if (!isset($cleared_progress['step_id'])) {
                $next = $key;
                break;
            }
            if ($step['value'] == $cleared_progress['step_id']) {
                $next = $key + 1;
                break;
            }
        }

        if (isset($steps[$next]['text'])) {
            $res = array(
                'text' => $steps[$next]['text'],
                'step_id' => $steps[$next]['value'],
                'progress_status' => null
            );
        } else {
            $res = [];
        }

        return $res;
    }

    private function updateReservation(array $data)
    {
        $reservation = $this->reservation->find($data['reservation_id']);
        if ($reservation === null) {
            throw new \Exception('対象の出席データが見つかりませんでした。');
        }
        $reservation->attendance_flg = $data['attendance_flg'] ?? 0;
        $reservation->teacher_evaluation = $data['evaluation']['teacher_evaluation'] ?? null;
        $reservation->evaluation_reason = $data['evaluation']['evaluation_reason'] ?? null;
        $this->reservation->save($reservation);
    }

    private function deleteProgress(int $student_id, array $progresses, string $date): void
    {
        $current = $this->progress->getStudentProgresses($student_id, $date);
        $not_delete = [];
        foreach ($current as $item) {
            foreach ($progresses as $progress) {
                if ($progress['id'] === $item->id) {
                    $not_delete[] = $progress['id'];
                    break;
                }
            }
            if (!in_array($item->id, $not_delete, true)) {
                $this->progress->delete($item->id);
            }
        }
    }

    private function checkCurriculum(Student $student): bool
    {
        $curriculum_id = request()->curriculum_id;
        if (!$curriculum_id) {
            return true;
        }
        $current = $this->step->getCurrentCurriculumId($student->id);
        return $current === (int)$curriculum_id;
    }

    public function getClearedStep(int $course_id, int $student_id)
    {


        $result = $this->progress->getCleared($course_id, $student_id);
        $result = $result->toArray();
        // 最新の進捗のカリキュラムが取れる
        $progress = end($result);

        // 終了しているカリキュラムがない場合
        if (!isset($progress['id'])) {
            return [];
        }

        // 最新の進捗のカリキュラムから最新の進捗情報を取得
        $result = $this->progress->getClearedStep($progress['id'], $student_id);

        return $result->toArray();
    }

    public function getEndCurriculums(int $course_id, int $student_id)
    {
        $result = $this->progress->getCleared($course_id, $student_id);
        return $result->toArray();
    }
}
