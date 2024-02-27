<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AbstractAdminController;
use Illuminate\Http\Request;
use App\Services\ReportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class ReportController extends AbstractAdminController
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

}
