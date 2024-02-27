<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AbstractAdminController;
use App\Services\CourseService;
use App\Services\TestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends AbstractAdminController
{
    private const CONTROLLER_NAME = 'コース';

    private CourseService $course;

    public function __construct(
        CourseService $course
    )
    {
        $this->course = $course;
        parent::__construct();
    }


    public function autocomplete(Request $request)
    {
        return response()->json($this->course->getAutocomplete());
    }

    public function autocompleteWithCompanyId(Request $request, $company_id)
    {
        return response()->json($this->course->getAutocompleteWithCompanyId($company_id));
    }

    public function autocompleteWithStudentId(Request $request, $student_id)
    {
        return response()->json($this->course->getAutocompleteWithStudentId($student_id));
    }

    /**
     * 一覧表示
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $course_list = $this->course->getList(null);
        return response()->json($course_list);
    }

    /**
     * 詳細表示
     * @return JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $result = $this->course->getById($id);
        return response()->json($result);
    }

    /**
     * 登録処理
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        if ($this->course->save($request)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を更新');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の更新');
    }

    /**
     * 更新処理
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if ($this->course->save($request, $id)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を更新');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の更新');
    }

    public function curriculum(Request $request, int $course_id)
    {
        try {
            return response()->json($this->course->getAssignedCurriculums($course_id));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('カリキュラム一覧の取得');
        }
    }

    public function saveCurriculum(Request $request, int $course_id)
    {
        try {
            if ($this->course->assignCurriculums($course_id, $request->all())) {
                return $this->successResponse('カリキュラムの登録');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('カリキュラムの登録');
        }

    }
}
