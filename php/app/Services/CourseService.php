<?php


namespace App\Services;

use App\Models\CourseCurriculum\CourseCurriculum;
use App\Models\CourseCurriculum\Repository\CourseCurriculumRepositoryInterface;
use App\Models\Student\Repository\StudentRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;


use App\Models\Course\Repository\CourseRepositoryInterface;

class CourseService extends AbstractService
{
    private CourseRepositoryInterface $course;
    private CourseCurriculumRepositoryInterface $courseCurriculum;
    private StudentRepositoryInterface $student;

    public function __construct(
        CourseRepositoryInterface $course,
        CourseCurriculumRepositoryInterface $courseCurriculum,
        StudentRepositoryInterface $student
    )
    {
        $this->course = $course;
        $this->courseCurriculum = $courseCurriculum;
        $this->student = $student;
    }

    public function getList(?int $company_id): array
    {
        return $this->course->getList($company_id);
    }

    public function getById(int $id): ?array
    {
        $ret = $this->course->find($id);
        return $ret !== null ? $ret->toArray() : null;
    }

    public function getAutocomplete()
    {
        return $this->course->getAutocompleteItem();
    }

    public function getAutocompleteWithCompanyId(?int $company_id)
    {
        return $this->course->getAutocompleteWithCompanyIdItem($company_id);
    }

    public function getAutocompleteWithStudentId(?int $student_id)
    {
        $student = $this->student->find($student_id);
        return $this->course->getAutocompleteWithCompanyIdItem($student->company_id);
    }

    /**
     * @param $request
     * @return bool
     */
    public function save(Request $request, $id = null)
    {
        try {
            DB::beginTransaction();
            try {
                // コースデータの登録
                $this->course->save($this->getModelAndSetParams($request, $id));
            } catch (Exception $e) {
                Log::error($e->getMessage());
                throw new \Exception('コース情報の登録に失敗しました');
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getModelAndSetParams(Request $request, int $id = null): Course
    {
        $course = new Course();
        if ($id !== null) {
            $course = $this->course->find($id);
        }
        $course->name = $request->name;
        $course->first_test_id = $request->first_test_id;
        $course->general_test_id = $request->general_test_id;
        return $course;
    }

    public function getAssignedCurriculums(int $course_id): array
    {
        return $this->courseCurriculum->getByCourseId($course_id)->toArray();
    }

    public function assignCurriculums(int $course_id, array $data): ?bool
    {
        try {
            DB::beginTransaction();
            // 既存紐付けを一旦削除
            $this->courseCurriculum->deleteByCourseId($course_id);
            foreach ($data as $order => $item) {
                $courseCurriculum = new CourseCurriculum();
                $courseCurriculum->course_id = $course_id;
                $courseCurriculum->curriculum_id = $item['id'];
                $courseCurriculum->order = $order;
                $this->courseCurriculum->save($courseCurriculum);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return true;
    }

}
