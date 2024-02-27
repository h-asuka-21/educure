<?php


namespace App\Services;


use App\Models\Company\Repository\CompanyRepositoryInterface;
use App\Models\Reservation\Repository\ReservationRepositoryInterface;
use App\Models\Schedule\Schedule;
use App\Models\Schedule\Repository\ScheduleRepositoryInterface;
use App\Models\TeacherSchedule\Repository\TeacherScheduleRepositoryInterface;
use App\Models\Student\Repository\StudentRepositoryInterface;
use App\Models\TeacherSchedule\TeacherSchedule;
use App\Models\User\Repository\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * Class ScheduleService
 * @package App\Services
 */
class ScheduleService extends AbstractService
{

    private ScheduleRepositoryInterface $schedule;
    private TeacherScheduleRepositoryInterface $teacher_schedule;
    private StudentRepositoryInterface $student;
    private ReservationRepositoryInterface $reservation;
    private ReportService $report;
    private UserRepositoryInterface $user;
    private CompanyRepositoryInterface $company;


    public function __construct(
        ScheduleRepositoryInterface $schedule,
        TeacherScheduleRepositoryInterface $teacher_schedule,
        StudentRepositoryInterface $student,
        ReservationRepositoryInterface $reservation,
        ReportService $report,
        UserRepositoryInterface $user,
        CompanyRepositoryInterface $company
    )
    {
        $this->schedule = $schedule;
        $this->teacher_schedule = $teacher_schedule;
        $this->student = $student;
        $this->reservation = $reservation;
        $this->report = $report;
        $this->user = $user;
        $this->company = $company;
    }

    /**
     * @param int|null $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        $schedule_list = $this->schedule->getList($company_id);
        foreach ($schedule_list['data'] as $key => $value) {
            // 出席数と予約数を取得
            $schedule_list['data'][$key]['attendance_count'] = $this->reservation->getAttendanceCount($value['id']);
            $schedule_list['data'][$key]['reserve_count'] = $this->reservation->getReserveCount($value['id']);
        }
        return $schedule_list;
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $ret = $this->schedule->find($id);
        return $ret !== null ? $ret->toArray() : null;
    }

    public function createScheduleForBatch(int $company_id)
    {
        try {
            DB::beginTransaction();
            $data['company_id'] = $company_id;
            $item = $this->createScheduleAndSetData($data);
            $result = $this->schedule->save($item);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getUsersByScheduleId(int $id)
    {
        return $this->teacher_schedule->getUsersByScheduleId($id);
    }

    public function getSchedulesForCalendar(string $date, int $company_id = null, ?int $student_id = null): array
    {
        $dateObj = Carbon::make($date)->firstOfMonth();
        $now = Carbon::now();
        $schedules = $this->schedule->getMonthlySchedule($dateObj, $company_id)->toArray();
        $result = [];
        foreach ($schedules as $key => $schedule) {
            // 現在の予約数
            $reserve_count = $this->reservation->getReserveCount($schedule['id']);
            $teachers = $this->getTeachers($schedule['id']);
            $item = [
                'id' => $schedule['id'],
                'name' => $schedule['name'],
                'student_count' => $schedule['student_count'],
                'reserve_count' => $reserve_count,
                'start' => $schedule['start'],
                'teachers' => $teachers['name'],
                'teacher_ids' => $teachers['id'],
                'end' => $schedule['end'],
                'color' => 'teal accent-4',
                'reservable' => true,
            ];
            if ($company_id === null) {
                $item['company_name'] = $schedule['company_name'];
                $item['company_id'] = $schedule['company_id'];
            }
            if ($student_id === null) {
                // 管理画面側はここで終了
                $item['reserved_students'] = $this->student->getReserveStudentList($schedule['id'], false);
                $item['attendance_count'] = $this->reservation->getAttendanceCount($schedule['id']);
                $result[] = $item;
                continue;
            }
            // 予約を取得
            $start = Carbon::make($schedule['end']);
            $reserve = $this->reservation->getStudentReserve($schedule['id'], $student_id);
            if ($reserve) {
                // 予約済みの場合
                $item['reservation_id'] = $reserve->id;
                $item['color'] = 'orange';
                $item['reserve_start'] = $reserve->start_time;
                $item['reserve_end'] = $reserve->end_time;
                $item['attendance_flg'] = $reserve->attendance_flg;
                if ($start->lte($now)) {
                    // 過去日
                    $item['color'] = 'grey';
                    $item['reservable'] = false;
                } elseif ($reserve->attendance_flg === 1) {
                    $item['color'] = 'cyan';
                    $item['reservable'] = false;
                }
                $result[] = $item;
                continue;
            }
            if ($item['student_count'] - $reserve_count <= 0 || $start->lte($now)) {
                // 枠がない、過去日
                $item['description'] = '予約受付終了';
                $item['color'] = 'grey';
                $item['reservable'] = false;
            }
            $result[] = $item;
        }
        return $result;
    }

    public function getTeachers(int $schedule_id): array
    {
        $teachers = $this->getUsersByScheduleId($schedule_id)->toArray();
        $ret = [
            'name' => [],
            'id' => [],
        ];
        foreach ($teachers as $teacher) {
            $ret['name'][] = $teacher['name'];
            $ret['id'][] = $teacher['id'];
        }
        return $ret;
    }

    public function getScheduleData(int $id, int $company_id): array
    {
        $schedule = $this->schedule->find($id);
        if ($schedule === null || $schedule->company_id !== $company_id) {
            throw new RouteNotFoundException('ページが見つかりませんでした。');
        }
        $result = $schedule->toArray();
        $result['user'] = [];
        $users = $this->teacher_schedule->getUsersByScheduleId($schedule->id);
        if ($users !== null) {
            foreach ($users as $user) {
                $result['user'][] = $user->user_id;
            }
        }
        return $result;
    }

    /**
     * @param array $data
     * @return Schedule
     */
    private function createScheduleAndSetData(array $data): Schedule
    {
        $schedule = new Schedule();
        $schedule->company_id = $data['company_id'];
        $schedule->name = '受講可能日';
        $schedule->date = Carbon::today()->format('Y-m-d');
        $schedule->start_time = '00:00:00';
        $schedule->end_time = '23:59:00';
        $schedule->available_limit = 100;
        return $schedule;
    }

    /**
     * @param int $user_id
     * @param int $schedule_id
     */
    private function saveTeacherSchedule(int $user_id, int $schedule_id): void
    {
        $teacher_schedule = new TeacherSchedule();
        $teacher_schedule->schedule_id = $schedule_id;
        $teacher_schedule->user_id = $user_id;
        $this->teacher_schedule->save($teacher_schedule);
    }

    /**
     * @param int $date
     * @param int $schedule_id
     */
    public function getScheduleFromDate(string $date, int $company_id)
    {
        $result = $this->schedule->getScheduleFromDate($date, $company_id);

        if (isset($result)) {
            return $result->toArray();
        }

        return $result;
    }

    public function bulkCreateSchedule(Request $data)
    {
        $date = Carbon::make($data->month)->startOfMonth();
        try {
            DB::beginTransaction();
            while ($date->format('Y-m-d') !== Carbon::make($data->month)->addMonth()->firstOfMonth()->format('Y-m-d')) {
                // 1ヶ月ループ
                $week = $date->dayOfWeek;
                // 対象の曜日じゃない場合はスキップ
                if (!$data->weeks[$week]) {
                    $date->addDay();
                    continue;
                }
                // 日付をリクエストにセット
                $data->merge([
                    'date' => $date->format('Y-m-d'),
                    'bulk' => true,
                ]);
                $this->createSchedule($data);
                $date->addDay();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
