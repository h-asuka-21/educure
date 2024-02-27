<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AbstractUserController;
use App\Services\StudentService;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use App\Services\MissingEvaluationItemService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;

class MissingEvaluationItemController extends AbstractUserController
{
    private const CONTROLLER_NAME = '日報';

    private MissingEvaluationItemService $missing_evaluation_item_service;
    private StudentService $student_service;
    private CompanyService $company_service;

    /**
     * InterviewHistoryController constructor.
     * @param MissingEvaluationItemService $missing_evaluation_item_service
     * @param StudentService $student_service
     * @param CompanyService $company_service
     */
    public function __construct
    (
        MissingEvaluationItemService $missing_evaluation_item_service,
        StudentService $student_service,
        CompanyService $company_service
    )
    {
        $this->missing_evaluation_item_service = $missing_evaluation_item_service;
        $this->student_service = $student_service;
        $this->company_service = $company_service;
        parent::__construct();
    }

    public function getListByCompanyId(): JsonResponse
    {
        try {
            $company_id = $this->getCompanyId();
            $list = $this->missing_evaluation_item_service->getListByCompanyId($company_id);
            return response()->json($list);
        } catch (\Exception $e) {
            return $this->errorResponse('不足評価の取得', $e->getMessage());
        }
    }

    /**
     * 詳細表示
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $interview_history_detail = $this->missing_evaluation_item_service->getById($id);
        return response()->json($interview_history_detail);
    }


}
