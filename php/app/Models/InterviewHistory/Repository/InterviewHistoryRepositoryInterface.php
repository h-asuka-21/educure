<?php


namespace App\Models\InterviewHistory\Repository;

use App\Models\InterviewHistory\InterviewHistory;
use Illuminate\Database\Eloquent\Builder;

interface InterviewHistoryRepositoryInterface
{
    public function getList(?int $interview_history_id): array;

    /**
     * @param int $id
     * @return InterviewHistory|null
     */
    public function find(int $id);


    /**
     * @param InterviewHistory $interview_history
     * @return bool
     */
    public function save(InterviewHistory $interview_history);

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @param int $student_id
     * @return int
     */
    public function getEvaluationAverageByStudentId(int $student_id): ?int;

    /**
     * Undocumented function
     *
     * @param integer $student_id
     * @param string $date
     * @return boolean
     */
    public function getEvaluationCountByStudentIdAndDate(int $student_id, string $date): bool;

    /**
     * @param int $student_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByStudentId(int $student_id);

}
