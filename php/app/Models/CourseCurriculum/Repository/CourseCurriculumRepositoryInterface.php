<?php


namespace App\Models\CourseCurriculum\Repository;


use App\Models\CourseCurriculum\CourseCurriculum;

interface CourseCurriculumRepositoryInterface
{
    public function getByCourseId(int $course_id);

    public function deleteByCourseId(int $course_id);

    public function save(CourseCurriculum $courseCurriculum): ?CourseCurriculum;
}
