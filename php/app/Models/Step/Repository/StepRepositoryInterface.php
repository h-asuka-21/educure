<?php


namespace App\Models\Step\Repository;

use App\Models\Step\Step;

interface StepRepositoryInterface
{
    public function getList(?int $curriculum_id): array;

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id);

    public function save(Step $step);

    public function delete(int $id): bool;

    public function getListByCurriculumId(int $curriculum_id): array;

    public function getAutocomplete(int $company_id);

    public function getStepList(int $student_id);

    /**
     * @param $curriculum_id
     * @param $student_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getStepsAndLatestProgressByCurriculumIdAndStudentId($curriculum_id, $student_id);

    public function getModelAndSetParams(array $data, ?int $curriculum_id, int $order): Step;

    public function getStepsDateCountByCurriculumAndStudent(int $curriculum_id, int $student_id);

    public function getCurrentCurriculumId(int $student_id);
}
