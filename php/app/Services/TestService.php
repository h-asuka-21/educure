<?php


namespace App\Services;


use App\Facades\CloudStorage;
use App\Models\Progress\Repository\ProgressRepositoryInterface;
use App\Models\Question\Repository\QuestionRepositoryInterface;
use App\Models\Test\Repository\TestRepositoryInterface;
use App\Models\Score\Repository\ScoreRepositoryInterface;
use App\Models\Test\Test;
use App\Models\MissingEvaluationItem\MissingEvaluationItem;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class TestService extends AbstractService
{

    private TestRepositoryInterface $test;
    private QuestionRepositoryInterface $question;
    private ScoreRepositoryInterface $score;
    private ProgressRepositoryInterface $progress;

    const QUESTION_IMAGE_PATH = '/question/images';

    public function __construct(
        TestRepositoryInterface $test,
        QuestionRepositoryInterface $question,
        ScoreRepositoryInterface $score,
        ProgressRepositoryInterface $progress
    )
    {
        $this->test = $test;
        $this->question = $question;
        $this->score = $score;
        $this->progress = $progress;
    }

    public function getList(?int $company_id): array
    {
        return $this->test->getList($company_id);
    }

    public function getById(int $id): ?array
    {
        $ret = $this->test->find($id);
        return $ret !== null ? $ret->toArray() : null;
    }

    public function getDetail(int $id): ?array
    {
        $test = $this->test->find($id);
        if ($test === null) {
            return null;
        }
        return [
            'test' => $test,
            'questions' => $this->question->getListByTestId($id),
        ];
    }

    public function updateCompany(Request $request, int $id)
    {
        $target_company = $this->company->find($id);

        if ($target_company === null) {
            return false;
        }

        $target_company->name = $request->name;
        $target_company->company_code = $request->company_code;

        return $this->company->save($target_company);
    }

    /**
     * @param Request $request
     * @param null $id
     * @return bool
     * @throws \Exception
     */
    public function save(Request $request, $id = null): bool
    {
        try {
            DB::beginTransaction();
            try {
                // テストデータの登録
                $test = $this->test->save($this->test->getModelAndSetParams($request->test, $id));
            } catch (Exception $e) {
                Log::error($e->getMessage());
                throw new \Exception('テスト情報の登録に失敗しました');
            }
            try {
                // 設問の削除
                $deleted = $request->deleted;
                if ($deleted !== null) {
                    foreach ($deleted as $question_id) {
                        $this->question->delete($question_id);
                    }
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                throw new \Exception("設問の削除に失敗しました。");
            }
            $questions = $request->questions;
            if ($questions === null) {
                // 設問なし
                DB::commit();
                return true;
            }
            try {
                // 設問の更新
                $num = 1;
                foreach ($questions as $key => $question) {
                    $num++;
                    if (isset($question['image']) && $question['image'] instanceof UploadedFile) {
                        $question['image'] = $this->saveImageAndGetPath($question['image']);
                    }
                    $this->question->save($this->question->getModelAndSetParams($question, $test->id, $key));
                }
                DB::commit();
                return true;
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                throw new \Exception("{$num}番目の設問登録に失敗しました。");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param UploadedFile $image
     * @param string $save_path
     * @return string|null
     */
    private function saveImageAndGetPath(UploadedFile $image): ?string
    {
        $image_name = Storage::disk('public')->putFile(self::QUESTION_IMAGE_PATH, $image);
        return Storage::disk('public')->url('') . '/' . $image_name;
    }

    private function formatQuestions(array $questions): array
    {
        return $questions;
    }

    /**
     * @param int $test_id
     * @return array
     */
    public function getStudentsByTestId(int $test_id): array
    {
        return $this->score->getStudentsByTestId($test_id);
    }

    public function getTestsWithScores($student_id): array
    {
        // 終了しているカリキュラム一覧を取得
        $cleared = $this->progress->getCreardCaluculums($student_id);
        $result = $this->test->getTestsWithScores($student_id, $cleared);
    }

    public function getEnableTestByCourseAndStudentId(?int $course_id, int $student_id)
    {
        if (is_null($course_id)) {
            throw new \Exception('コースが未設定です。');
        }
        // CABテスト（一番最初に受験するテスト）を受けているか確認
        $cab_test = $this->test->getUnansweredTest($student_id, Test::TYPE['cab']);
        if ($cab_test !== null) {
            return $cab_test->toArray();
        }

        $last_cleared = $this->progress->getCleared($course_id, $student_id)->last();
        if (!empty($last_cleared)) {
            $test = $this->test->getUnansweredTest($student_id, Test::TYPE['curriculum'], $last_cleared->id);
            if ($test !== null) {
                return $test->toArray();
            }
        } else {
            return;
        }

        // この時点でテストがなければ総合テストを取得
        return $this->test->getUnansweredTest($student_id, Test::TYPE['comp'], null, $course_id);
    }

    public function getDetailByStudentId(int $id, ?int $student_id): ?array
    {
        // スコアレコードがあったらnullを返す
        $score = $this->score->getRecordByStudentIdAndTestId($student_id, $id);
        $test = $this->test->find($id);
        if ($test === null) {
            return null;
        }
        $questions = $this->question->getListByTestId($id);
        foreach ($questions as $key => $question) {
            $questions[$key]['choices'] = 0;
        }
        return [
            'test' => $test,
            'questions' => $questions,
        ];
    }

    public function getAutocomplete(?int $type = null): array
    {
        return $this->test->getAutocomplete($type);
    }

    public function checkUnAnsweredCurriculumTest(int $student_id, array $cleared_curriculums)
    {
        $result = array();
        $scores = array();

        foreach ($cleared_curriculums as $curriculum) {
            // テスト未設定のカリキュラムはスキップ
            if (empty($curriculum['test_id'])) {
                continue;
            }

            $test = $this->test->find($curriculum['test_id']);

            $scores[] = array(
                'curriculum_id' => $curriculum['id'],
                'curriculum_name' => $curriculum['name'],
                'test_id' => $curriculum['test_id'],
                'test_name' => $test->name,
                'score' => $this->score->getRecordByStudentIdAndTestId($student_id, $curriculum['test_id']),
            );
        }


        foreach ($scores as $score) {
            // テスト結果がNULLのデータ、未受験を選別
            if (!is_null($score['score'])) {
                continue;
            }

            $result[] = array(
                'student_id' => $student_id,
                'missing_type' => MissingEvaluationItem::TYPE['comprehension_score'],
                'reason' => "カリキュラムテスト「" . $score['test_name'] . "」が未受験です。"
            );
        }

        return $result;
    }

    public function checkUnAnsweredCabTest($student_id)
    {
        $result = array();

        $cab_test = $this->test->getUnansweredTest($student_id, Test::TYPE['cab']);

        if ($cab_test !== null) {
            $result = array(
                'student_id' => $student_id,
                'missing_type' => MissingEvaluationItem::TYPE['think_score'],
                'reason' => "CABテスト「" . $cab_test->name . "」が未受験です。"
            );
        }

        return $result;
    }


}
