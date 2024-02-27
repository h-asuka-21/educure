<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\AbstractAdminController;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class StudentController extends AbstractAdminController
{
    private const CONTROLLER_NAME = '生徒';

    private StudentService $student_service;

    /**
     * StudentController constructor.
     * @param StudentService $student_service
     */
    public function __construct
    (
        StudentService $student_service
    )
    {
        $this->student_service = $student_service;
        parent::__construct();
    }

    /**
     * 詳細表示
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $student_detail = $this->student_service->getList($id);
        return response()->json($student_detail);
    }


    /**
     * 登録処理
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        if ($this->student_service->save($request, null)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'の登録');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の登録');
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
        if ($this->student_service->save($request, null, $id)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'の更新');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の更新');
    }

    /**
     * 削除処理
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if ($this->student_service->deleteStudent($id)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'の削除');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の削除');
    }

    public function detail(int $id)
    {
        try {
            $student_detail = $this->student_service->getById($id);
            return response()->json($student_detail);
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            return $this->errorResponse('生徒データの取得');
        }
    }

    public function evaluation(Request $request, int $id): JsonResponse
    {
        try {
            return response()->json($this->student_service->getEvaluation($id));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得', $e->getMessage(), null, $e->getCode() || 500);
        }
    }

    public function teacher_evalution(Request $request, int $id): JsonResponse
    {
        try {
            return response()->json($this->student_service->getTeacherEvaluationData($id));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得', $e->getMessage(), null, $e->getCode() || 500);
        }
    }

    public function student_statistics(Request $request): JsonResponse
    {
        try {
            return response()->json($this->student_service->getStudentStatistics($request->company_id));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得', $e->getMessage(), null, $e->getCode() || 500);
        }
    }

    public function company_statistics(Request $request): JsonResponse
    {
        try {
            return response()->json($this->student_service->getCompanyStatistics());
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得', $e->getMessage(), null, $e->getCode() || 500);
        }
    }

    public function companyRanking(Request $request)
    {
        try {
            return response()->json($this->student_service->getCompanyRankingByAchievementPercentage($request->order));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得');
        }
    }
}
