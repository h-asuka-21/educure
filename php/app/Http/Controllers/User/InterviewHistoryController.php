<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AbstractUserController;
use Illuminate\Http\Request;
use App\Services\InterviewHistoryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;

class InterviewHistoryController extends AbstractUserController
{
    private const CONTROLLER_NAME = '日報';

    private InterviewHistoryService $interview_history_service;

    /**
     * InterviewHistoryController constructor.
     * @param InterviewHistoryService $interview_history_service
     */
    public function __construct
    (
        InterviewHistoryService $interview_history_service
    )
    {
        $this->interview_history_service = $interview_history_service;
        parent::__construct();
    }

    /**
     * 一覧表示
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $company_id = $this->getCompanyId();
        $interview_history_list = $this->interview_history_service->getList($company_id);
        return response()->json($interview_history_list);
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
            $this->interview_history_service->createInterviewHistory($request, $this->getId());
            return $this->successResponse(self::CONTROLLER_NAME . 'を登録');
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', $e->getMessage(), 401);
        } catch (\Exception $e) {
            return $this->errorResponse(self::CONTROLLER_NAME . 'の登録');
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
            $this->interview_history_service->updateInterviewHistory($request, $this->getId(), (int)$id);
            return $this->successResponse(self::CONTROLLER_NAME . 'を登録');
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', $e->getMessage(), 401);
        } catch (\Exception $e) {
            return $this->errorResponse(self::CONTROLLER_NAME . 'の登録');
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
        if ($this->interview_history_service->deleteInterviewHistory($id)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を削除');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の削除');
    }

    public function getListByStudentId(int $student_id): JsonResponse
    {
        $interview_history_list = $this->interview_history_service->getListByStudentId($student_id);
        return response()->json($interview_history_list);
    }

}
