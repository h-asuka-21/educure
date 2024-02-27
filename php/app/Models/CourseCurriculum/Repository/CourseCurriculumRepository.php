<?php


namespace App\Models\CourseCurriculum\Repository;

use App\Models\AbstractRepository;
use App\Models\CourseCurriculum\CourseCurriculum;

class CourseCurriculumRepository extends AbstractRepository implements CourseCurriculumRepositoryInterface
{
    public function getByCourseId(int $course_id)
    {
        return CourseCurriculum::query()
            ->select([
                'curriculums.id as id',
                'curriculums.name as name'
            ])
            ->join('curriculums', 'curriculums.id', 'course_curriculums.curriculum_id')
            ->where('course_curriculums.course_id', $course_id)
            ->orderBy('course_curriculums.order', 'asc')
            ->get();
    }

    public function deleteByCourseId(int $course_id)
    {
        return CourseCurriculum::query()
            ->where('course_id', $course_id)
            ->delete();
    }

    /**
     * @param CourseCurriculum $courseCurriculum
     * @return CourseCurriculum|null
     */
    public function save(CourseCurriculum $courseCurriculum): ?CourseCurriculum
    {
        return CourseCurriculum::query()->updateOrCreate(
            ['id' => $courseCurriculum->id],
            [
                'course_id' => $courseCurriculum->course_id,
                'curriculum_id' => $courseCurriculum->curriculum_id,
                'order' => $courseCurriculum->order,
            ]
        );
    }

}
