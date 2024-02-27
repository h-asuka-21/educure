<?php


namespace App\Models\CourseGroup\Repository;

use App\Models\AbstractRepository;
use App\Models\CourseGroup\CourseGroup;

class CourseGroupRepository extends AbstractRepository implements CourseGroupRepositoryInterface
{
    public function getCoursesByCompanyId(int $company_id)
    {
        return CourseGroup::query()
            ->select([
                'courses.id'
            ])
            ->join('courses', 'course_groups.course_id', 'courses.id')
            ->where('course_groups.company_id', $company_id)
            ->get();
    }

    public function deleteByCompanyId(int $company_id)
    {
        return CourseGroup::query()
            ->where('company_id', $company_id)
            ->delete();
    }

    /**
     * @param CourseGroup $course_group
     * @return CourseGroup|null
     */
    public function save(CourseGroup $course_group): ?CourseGroup
    {
        return CourseGroup::query()->updateOrCreate(
            ['id' => $course_group->id],
            [
                'course_id' => $course_group->course_id,
                'company_id' => $course_group->company_id,
            ]
        );
    }

}
