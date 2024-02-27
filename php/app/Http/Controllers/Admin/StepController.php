<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AbstractAdminController;
use App\Services\StepService;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class StepController extends AbstractAdminController
{
    private const CONTROLLER_NAME = 'ステップ';

    private StepService $step_service;
    private StudentService $student;

    public function __construct(
        StepService $step_service,
        StudentService $student
    )
    {
        $this->step_service = $step_service;
        $this->student = $student;
        parent::__construct();
    }

    /**
     * 一覧表示
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $step_list = $this->step_service->getList(null);
        return response()->json($step_list);
    }

    /**
     * 詳細表示
     * @return JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $result = $this->step_service->getById($id);
        if ($result) {
            return response()->json($result);
        }
        return $this->errorResponse('', '', null, 404);
    }

    /**
     * @param $curriculum_id
     * @return JsonResponse
     */
    public function getListByCurriculumIdWithPagenate($curriculum_id): JsonResponse
    {
        $step_list = $this->step_service->getList($curriculum_id);
        return response()->json($step_list);
    }

    public function autocomplete(Request $request)
    {
        try {
            $student = $this->student->getById($request->student_id);
            return response()->json($this->step_service->getAutocomplete($student['company_id']));
        } catch (UnauthorizedException $e) {
            return $this->authFailedResponse();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('コース一覧の取得');
        }
    }

}
