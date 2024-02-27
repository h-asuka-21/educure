<?php


namespace App\Models\Curriculum\Repository;

use App\Models\Curriculum\Curriculum;
use Illuminate\Database\Eloquent\Builder;

interface CurriculumRepositoryInterface
{
    public function getList(?int $company_id): array;

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id);

    /**
     * @param Curriculum $test
     * @return Curriculum|false
     */
    public function save(Curriculum $curriculum);

    public function getAutocompleteItem(): array;

    public function getAutocompleteItemWithCompanyId(int $company_id): array;

    /**
     * @param int $course_id
     * @param bool $paginate
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByCourseId(int $course_id, bool $paginate = false);

    public function getCurriculumTarget(int $curriculum_id): int;
}

