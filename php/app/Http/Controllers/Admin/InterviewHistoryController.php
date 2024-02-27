<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AbstractAdminController;
use App\Services\StudentService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Services\InterviewHistoryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;

class InterviewHistoryController extends AbstractAdminController
{
    private const CONTROLLER_NAME = '日報';

    private InterviewHistoryService $interview_history_service;
    private StudentService $student;
    private UserService $user;

    /**
     * InterviewHistoryController constructor.
     * @param InterviewHistoryService $interview_history_service
     * @param StudentService $student
     * @param UserService $user
     */
    public function __construct
    (
        InterviewHistoryService $interview_history_service,
        StudentService $student,
        UserService $user
    )
    {
        $this->interview_history_service = $interview_history_service;
        $this->student = $student;
        $this->user = $user;
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
        $interview_history_detail = $this->interview_history_service->getById($id);
        return response()->json($interview_history_detail);
    }

    /**
     * 登録処理
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $this->interview_history_service->createInterviewHistory($request, $this->getAdminUserByStudentId($request)['id']);
            return $this->successResponse(self::CONTROLLER_NAME . 'を登録');
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', $e->getMessage(), 401);
        } catch (\Exception $e) {
            return $this->errorResponse(self::CONTROLLER_NAME . 'の登録', $e->getMessage());
        }
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
        try {
            $this->interview_history_service->updateInterviewHistory($request, $this->getAdminUserByStudentId($request)['id'], (int)$id);
            return $this->successResponse(self::CONTROLLER_NAME . 'を登録');
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', $e->getMessage(), 401);
        } catch (\Exception $e) {
            return $this->errorResponse(self::CONTROLLER_NAME . 'の登録', $e->getMessage());
        }
    }

    /**
     * 削除処理
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->interview_history_service->deleteInterviewHistory($id);
            return $this->successResponse(self::CONTROLLER_NAME . 'を削除');
        } catch (\Exception $e) {
            return $this->errorResponse(self::CONTROLLER_NAME . 'の削除', $e->getMessage());
        }
    }

    public function getListByStudentId(int $student_id): JsonResponse
    {
        try {
            $interview_history_list = $this->interview_history_service->getListByStudentId($student_id);
            return response()->json($interview_history_list);
        } catch (\Exception $e) {
            return $this->errorResponse('面談履歴の取得', $e->getMessage());
        }
    }

    /**
     * @param $request
     * @return array|null
     * @throws \Exception
     */
    private function getAdminUserByStudentId($request): ?array
    {
        $student = $this->student->getById($request->student_id);
        return $this->user->getAdminUserByCompanyId($student['company_id']);
    }
}
