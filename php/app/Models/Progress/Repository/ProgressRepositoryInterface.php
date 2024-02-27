<?php


namespace App\Models\Progress\Repository;

use App\Models\Progress\Progress;
use Illuminate\Database\Eloquent\Builder;

interface ProgressRepositoryInterface
{
    public function getList(?int $company_id): array;

    /**
     * @param int $id
     * @return Progress|null
     */
    public function find(int $id);


    /**
     * @param Progress $progress
     * @return Progress
     */
    public function save(Progress $progress);

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    public function getCurriculumDateCount(int $student_id, int $curriculum_id): ?int;

    /**
     * @param int $curriculum_id
     * @return int|string|null
     */
    public function getCurriculumAverage(int $curriculum_id);

    public function getStepAverage(int $step_id);

    /**
     * @param int $student_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getStudentProgresses(int $student_id, ?string $date = null);

    /**
     * @param int $reservation_id
     * @param bool $with_curriculum_name
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getProgressesByReservations(int $reservation_id, bool $with_curriculum_name = false, ?int $course_id = 0);

    /**
     * @param $step_id
     * @param $student_id
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getLatestStatusByStepIdAndStudentId($step_id, $student_id);

    /**
     * コース内のクリア済み（すべてのステップの進捗が100%）のカリキュラムを取得
     * @param int $course_id
     * @param int $student_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getCleared(int $course_id, int $student_id);

    public function getClearedStep(int $curriculum_id, int $student_id);

    public function getPrgoressed(int $student_id);

    public function getInProgress(int $student_id);

    public function getClearedStepWithStudentId(int $student_id);

}
