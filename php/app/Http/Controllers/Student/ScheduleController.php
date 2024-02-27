<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\AbstractStudentController;
use App\Models\Schedule\Schedule;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class ScheduleController extends AbstractStudentController
{
    private const CONTROLLER_NAME = 'スケジュール';

    private ScheduleService $schedule_service;

    /**
     * ScheduleController constructor.
     * @param ScheduleService $schedule_service
     */
    public function __construct
    (
        ScheduleService $schedule_service
    )
    {
        $this->schedule_service = $schedule_service;
        parent::__construct();
    }

    /**
     * 一覧表示
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $company_id = $this->getCompanyId();
            $student_id = $this->getId();
            $schedules = $this->schedule_service->getSchedulesForCalendar($request->date, $company_id, $student_id);
            return response()->json($schedules);
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->errorResponse('スケジュールの取得');
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
        $schedule_detail = $this->schedule_service->getById($id);
        return response()->json($schedule_detail);
    }

    /**
     * 登録処理
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        if ($this->schedule_service->createSchedule($request)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を登録');
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
        if ($this->schedule_service->updateSchedule($request, (int)$id)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を更新');
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
        if ($this->schedule_service->deleteSchedule($id)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を削除');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の削除');
    }

}
