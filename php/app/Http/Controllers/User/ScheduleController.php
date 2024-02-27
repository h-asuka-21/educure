<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AbstractUserController;
use App\Services\ReservationService;
use App\Services\StudentService;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class ScheduleController extends AbstractUserController
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

    /**
     * 一覧表示
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $company_id = $this->getCompanyId();
        $schedule_list = $this->schedule_service->getList($company_id);
        return response()->json($schedule_list);
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
            return response()->json($this->schedule_service->getScheduleData($id, $company_id));
        } catch (RouteNotFoundException $e) {
            // 自企業のスケジュールじゃないまたはスケジュールがない
            return $this->errorResponse('', 'データが見つかりませんでした。', null, 404);
        } catch (\Exception $e) {
            //認証エラー
            return $this->errorResponse('', '', null, 401);
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


    public function calendar(Request $request)
    {
        try {
            $company_id = $this->getCompanyId();
        } catch (\Exception $e) {
            return $this->errorResponse('', '', null, 401);
        }
        return response()->json($this->schedule_service->getSchedulesForCalendar($request->date, $company_id));
    }

    public function reservedStudents(Request $request, $id)
    {

        try {
            $company_id = $this->getCompanyId();
            $this->schedule_service->getScheduleData($id, $company_id);
            return response()->json($this->student_service->getReservedStudents($id));
        } catch (RouteNotFoundException $e) {
            // 自企業のスケジュールじゃないまたはスケジュールがない
            return $this->errorResponse('', 'データが見つかりませんでした。', null, 404);
        } catch (UnauthorizedException $e) {
            //認証エラー
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            return $this->errorResponse('', '予約者データの取得に失敗しました。しばらくしてからもう一度お試しください。');
        }
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
