<?php


namespace App\Services;


use App\Models\InterviewHistory\InterviewHistory;
use App\Models\InterviewHistory\Repository\InterviewHistoryRepositoryInterface;
use App\Models\MissingEvaluationItem\MissingEvaluationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class InterviewHistoryService extends AbstractService
{

    private InterviewHistoryRepositoryInterface $interview_history;

    public function __construct(
        InterviewHistoryRepositoryInterface $interview_history
    )
    {
        $this->interview_history = $interview_history;
    }

    /**
     * @param int|null $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        $interview_history_list = $this->interview_history->getList($company_id);
        foreach ($interview_history_list['data'] as $key => $value) {
            $interview_history_list['data'][$key]['evaluation_average'] = $this->interview_history->getEvaluationAverageByStudentId($value['student_id']);
        }
        return $interview_history_list;
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $ret = $this->interview_history->find($id);
        return $ret !== null ? $ret->toArray() : null;
    }

    /**
     * @param $request
     * @return bool
     */
    public function createInterviewHistory(Request $request, int $user_id): bool
    {
        $interview_history = new InterviewHistory();
        $interview_history->student_id = $request->student_id;
        $interview_history->sales_evaluation = $request->sales_evaluation;
        $interview_history->evaluation_reason = $request->evaluation_reason;
        $interview_history->user_id = $user_id;

        return $this->interview_history->save($interview_history);
    }

    /**
     * @param Request $request
     * @param int $user_id
     * @param int $id
     * @return bool
     */
    public function updateInterviewHistory(Request $request, int $user_id, int $id): bool
    {
        $target_interview_history = $this->interview_history->find($id);
        if ($target_interview_history === null) {
            return false;
        }
        $target_interview_history->student_id = $request->student_id;
        $target_interview_history->sales_evaluation = $request->sales_evaluation;
        $target_interview_history->evaluation_reason = $request->evaluation_reason;
        $target_interview_history->user_id = $user_id;
        return $this->interview_history->save($target_interview_history);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteInterviewHistory(int $id)
    {
        $target_interview_history = $this->interview_history->find($id);
        if ($target_interview_history === null) {
            return false;
        }
        return $this->interview_history->delete($id);
    }

    public function getListByStudentId(int $student_id): array
    {
        return $this->interview_history->getListByStudentId($student_id)->toArray();
    }

    public function getEvaluationCountByStudentIdAndDate(int $student_id, string $date): bool
    {
        return $this->interview_history->getEvaluationCountByStudentIdAndDate($student_id, $date);
    }

    public function checkUnInterviewHistory(int $student_id)
    {
        $result = array();

        $interview_history = $this->interview_history->getListByStudentId($student_id)->toArray();

        // 営業面談がない場合
        if (empty($interview_history)) {
            $result = array(
                'student_id' => $student_id,
                'missing_type' => MissingEvaluationItem::TYPE['sales_score'],
                'reason' => "営業面談がされていません。"
            );
        }

        return $result;
    }

}
