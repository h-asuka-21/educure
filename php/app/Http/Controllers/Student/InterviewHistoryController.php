<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\AbstractStudentController;
use Illuminate\Http\Request;
use App\Services\InterviewHistoryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;

class InterviewHistoryController extends AbstractStudentController
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


    public function getListByStudentId(Request $request): JsonResponse
    {
        try {
            $interview_history_list = $this->interview_history_service->getListByStudentId($this->getId());
            return response()->json($interview_history_list);
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', $e->getMessage(), 401);
        } catch (\Exception $e) {
            return $this->errorResponse('面談履歴の取得', $e->getMessage(), 500);
        }
    }

}
