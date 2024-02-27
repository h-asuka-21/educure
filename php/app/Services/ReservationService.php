<?php


namespace App\Services;


use App\Models\Progress\Repository\ProgressRepositoryInterface;
use App\Models\Reservation\Repository\ReservationRepositoryInterface;
use App\Models\Reservation\Reservation;
use App\Models\Student\Repository\StudentRepositoryInterface;
use App\Models\MissingEvaluationItem\MissingEvaluationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationService extends AbstractService
{
    private ReservationRepositoryInterface $reservation;
    private StudentRepositoryInterface $student;
    private ProgressRepositoryInterface $progress;

    public function __construct(
        ReservationRepositoryInterface $reservation,
        StudentRepositoryInterface $student,
        ProgressRepositoryInterface $progress
    )
    {
        $this->reservation = $reservation;
        $this->student = $student;
        $this->progress = $progress;
    }

    public function getList(?int $company_id): array
    {
        return [];
    }

    public function getById(int $id): ?array
    {
        return [];
    }

    public function reserveForBatch(int $student_id, int $schedule_id)
    {
        try {
            DB::beginTransaction();
            $reserve = new Reservation();
            $reserve->schedule_id = $schedule_id;
            $reserve->student_id = $student_id;
            $reserve->start_time = '00:00:00';
            $reserve->end_time = '00:00:00';
            $reserve->attendance_flg = 0;

            $this->reservation->save($reserve);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getTodayReserveAndReport($student_id): array
    {
        $result = $this->reservation->getReserveAndReportByStudentIdAndDate($student_id);
        if (empty($result)) {
            return [];
        }
        return $result->toArray();
    }

    public function getReservedStudentsPerDate(Request $request, ?int $company_id = null): array
    {
        // 表示開始日
        $start = Carbon::now();
        if ($request->start_date) {
            $start = Carbon::make($request->start_date);
        }
        $result = [];
        for ($i = 0; $i <= 6; $i++) {
            $target_date = Carbon::make($start)->addDays($i);
            $result[$target_date->format('Y-m-d')] = $this->student->getReservedStudentByDate($target_date, $company_id)->toArray();
        }
        return $result;
    }

    public function getReservedStudentIds(int $schedule_id): array
    {
        $ret = $this->reservation->getReservedStudentIds($schedule_id)->toArray();
        return $ret;
    }

    public function checkUnTeacherScore(int $student_id, string $date)
    {
        $result = array();

        $teacher_score_count = $this->reservation->getTeacherEvaluationCount($student_id, $date);

        if (empty($teacher_score_count)) {
            $result = array(
                'student_id' => $student_id,
                'missing_type' => MissingEvaluationItem::TYPE['teacher_score'],
                'reason' => "講師評価が入力されていません。"
            );
        }

        return $result;
    }

    public function checkUnAttendance(int $student_id, string $date)
    {
        $result = array();

        $attendance_count = $this->reservation->getAttendanceCountByDate($student_id, $date);

        if (empty($attendance_count)) {
            $result = array(
                'student_id' => $student_id,
                'missing_type' => MissingEvaluationItem::TYPE['attendance_score'],
                'reason' => "出席が入力されていません。"
            );
        }

        return $result;
    }


    public function checkUnReport(int $student_id, string $date)
    {
        $result = array();

        $report_count = $this->reservation->getTotalStudentReserveCountWithTeacherEvaluationToDate($student_id, $date);

        if (empty($report_count)) {
            $result = array(
                'student_id' => $student_id,
                'missing_type' => MissingEvaluationItem::TYPE['report_score'],
                'reason' => "報告がありません。"
            );
        }

        return $result;
    }

}
