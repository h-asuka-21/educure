<?php


namespace App\Models\Course\Repository;

use App\Models\Course\Course;

interface CourseRepositoryInterface
{
    public function getList(?int $company_id): array;

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id);


    /**
     * @param Course $course
     * @return bool
     */
    public function save(Course $course);

    public function getAutocompleteItem(): array;

    public function getAutocompleteWithCompanyIdItem(?int $company_id): array;
}
