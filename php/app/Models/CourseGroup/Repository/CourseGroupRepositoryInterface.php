<?php


namespace App\Models\CourseGroup\Repository;


use App\Models\CourseGroup\CourseGroup;

interface CourseGroupRepositoryInterface
{
    public function getCoursesByCompanyId(int $company_id);

    public function deleteByCompanyId(int $company_id);

    public function save(CourseGroup $course_group): ?CourseGroup;
}
