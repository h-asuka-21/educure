<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AbstractAdminController;
use App\Services\ReservationService;
use App\Services\ScheduleService;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleController extends AbstractAdminController
{
    private const CONTROLLER_NAME = 'スケジュール';

    private ScheduleService $schedule_service;
    private ReservationService $reservation_service;
    private StudentService $student_service;

    /**
     * ScheduleController constructor.
     * @param ScheduleService $schedule_service
     */
    public function __construct
    (
        ScheduleService $schedule_service,
        ReservationService $reservation_service,
        StudentService $student_service
    )
    {
        $this->schedule_service = $schedule_service;
        $this->reservation_service = $reservation_service;
        $this->student_service = $student_service;
        parent::__construct();
    }

    public function calendar(Request $request)
    {
        try {
            return response()->json($this->schedule_service->getSchedulesForCalendar($request->date));
        } catch (\Exception $e) {
            Log::error($e);
            return $this->errorResponse(self::CONTROLLER_NAME . 'の取得');
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
        try {
            $this->schedule_service->createSchedule($request);
            return $this->successResponse(self::CONTROLLER_NAME . 'を登録');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
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
            $this->schedule_service->updateSchedule($request, (int)$id);
            return $this->successResponse(self::CONTROLLER_NAME . 'を更新');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse(self::CONTROLLER_NAME . 'の更新');
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
        if ($this->schedule_service->deleteSchedule($id)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を削除');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の削除');
    }

    public function bulk(Request $request)
    {
        try {
            $this->schedule_service->bulkCreateSchedule($request);
            return $this->successResponse(self::CONTROLLER_NAME . 'の一括登録');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse(self::CONTROLLER_NAME . 'の一括登録');
        }
    }


}
