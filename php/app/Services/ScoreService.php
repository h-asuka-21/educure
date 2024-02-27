<?php


namespace App\Services;


use App\Facades\CloudStorage;
use App\Models\Progress\Repository\ProgressRepositoryInterface;
use App\Models\Question\Repository\QuestionRepositoryInterface;
use App\Models\Score\Repository\ScoreRepositoryInterface;
use App\Models\Score\Score;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class ScoreService extends AbstractService
{
    private ScoreRepositoryInterface $score;

    public function __construct(
        ScoreRepositoryInterface $score
    )
    {
        $this->score = $score;
    }

    public function getList(?int $company_id = null): array
    {
    }


    public function getById(int $id): ?array
    {
        $ret = $this->score->find($id);
        return $ret !== null ? $ret->toArray() : null;
    }

    /**
     * @param Request $request
     * @param int|null $student_id
     * @param int|null $score_id
     * @return array|null
     * @throws \Exception
     */
    public function save(Request $request, int $student_id = null, ?int $score_id = null): ?array
    {
        try {
            DB::beginTransaction();
            $request_data = $request->toArray();
            $test_id = $request_data['test']['id'];
            $questions = $request_data['questions'];
            // 正解と比較してスコアを算出
            [$score, $choices] = $this->getScoreCompareAndChoicesWithAnswer($questions);
            $result = $this->score->save($this->score->getModelAndSetParams([
                'score_id' => $score_id,
                'student_id' => $student_id,
                'test_id' => $test_id,
                'score' => $score,
                'choices' => $choices
            ]));
            DB::commit();
            return $result->toArray();
        } catch (\Exception $e) {
            DB::rollBack();
            if ($score_id) {
                // 更新時例外が発生したら、回答データを削除して、終了する。
                $this->score->delete($score_id);
            }
            Log::error($e->getMessage());
            throw new \Exception('スコアの登録に失敗しました');
        }
    }

    public function getScoreCompareAndChoicesWithAnswer($questions)
    {
        $score = 0;
        $choices = [];
        foreach ($questions as $key => $value) {
            $choices[] = isset($value['choices']) ? $value['choices'] : 0;
            if (isset($value['choices']) && $value['answer'] == $value['choices']) {
                $score++;
            }
        }
        $choices = implode(',', $choices);
        return [$score, $choices];
    }

}
