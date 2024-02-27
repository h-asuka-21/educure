<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AbstractUserController;
use Illuminate\Http\Request;
use App\Services\ReportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;

class ReportController extends AbstractUserController
{
    private const CONTROLLER_NAME = '日報';

    private ReportService $report_service;

    /**
     * ReportController constructor.
     * @param ReportService $report_service
     */
    public function __construct
    (
        ReportService $report_service
    )
    {
        $this->report_service = $report_service;
        parent::__construct();
    }

    /**
     * 一覧表示
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $company_id = $this->getCompanyId();
        $report_list = $this->report_service->getList($company_id);
        return response()->json($report_list);
    }

    /**
     * 詳細表示
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $report_detail = $this->report_service->getById($id);
        return response()->json($report_detail);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getUserByReportId(int $id)
    {
        return $this->report_service->getUserByReportId($id);
    }

}
