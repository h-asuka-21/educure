<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AbstractUserController;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\AbstractAdminController;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class StudentController extends AbstractUserController
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

    public function index(Request $request)
    {
        try {
            $company_id = $this->getCompanyId();
        } catch (\Exception $e) {
            return $this->errorResponse('', '', null, 401);
        }
        return response()->json($this->student_service->getList($company_id));
    }

    /**
     * 詳細表示
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $company_id = $this->getCompanyId();
            $student_detail = $this->student_service->getById($id);
            if ($student_detail !== null && $company_id === $student_detail['company_id']) {
                return response()->json($student_detail);
            } else {
                throw new \Exception('Not Found', 404);
            }
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            return $this->errorResponse('', $e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * 登録処理
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $action = self::CONTROLLER_NAME . 'の登録';
        try {
            $company_id = $this->getCompanyId();
        } catch (\Exception $e) {
            return $this->errorResponse('', '', null, 401);
        }
        if ($this->student_service->save($request, $company_id)) {
            return $this->successResponse($action);
        }
        return $this->errorResponse($action);
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
        $action = self::CONTROLLER_NAME . 'の更新';
        try {
            $company_id = $this->getCompanyId();
        } catch (\Exception $e) {
            return $this->errorResponse('', '', null, 401);
        }
        if ($this->student_service->save($request, $company_id, $id)) {
            return $this->successResponse($action);
        }
        return $this->errorResponse($action);
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
            return $this->successResponse(self::CONTROLLER_NAME . 'を削除');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の削除');
    }

    public function evaluation(Request $request, int $id): JsonResponse
    {
        try {
            $company_id = $this->getCompanyId();
            return response()->json($this->student_service->getEvaluation($id, $company_id));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得', '', null, 500);
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

    /**
     * 遅延している生徒一覧を取得
     * @param Request $request
     */
    public function getDelayStudents(Request $request)
    {
        try {
            $company_id = $this->getCompanyId();
            $result = $this->student_service->getDelayStudents($company_id);
            return response()->json($result);
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得', $e->getMessage());
        }

    }

    /**
     * 評価が低い生徒一覧を取得
     * @param Request $request
     */
    public function getLowEvaluationStudents(Request $request)
    {
        try {
            $company_id = $this->getCompanyId();
            $result = $this->student_service->getLowEvaluationStudents($company_id);
            return response()->json($result);
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得', $e->getMessage());
        }
    }

    /**
     * 1ヶ月以上参加していない生徒一覧を取得
     * @param Request $request
     */
    public function getNotAttendedStudents(Request $request)
    {
        try {
            $company_id = $this->getCompanyId();
            $result = $this->student_service->getNotAttendedStudents($company_id);
            return response()->json($result);
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得', $e->getMessage());
        }
    }

    public function evaluationRanking(Request $request): JsonResponse
    {
        try {
            $company_id = $this->getCompanyId();
            return response()->json($this->student_service->getEvaluationRanking($company_id));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得');
        }
    }
}
