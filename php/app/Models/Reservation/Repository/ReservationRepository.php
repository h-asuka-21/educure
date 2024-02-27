<?php


namespace App\Models\Reservation\Repository;

use App\Models\AbstractRepository;
use App\Models\Question\Question;
use App\Models\Reservation\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationRepository extends AbstractRepository implements ReservationRepositoryInterface
{
    /**
     * @param int $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        $query = request()->query();
        return Question::query()
            ->paginate($this->getPerPage($query))
            ->toArray();
    }

    /**
     * @param int $id
     * @return Reservation|null
     */
    public function find(int $id)
    {
        return Reservation::query()->find($id);
    }


    public function save(Reservation $reservation)
    {
        return DB::transaction(function () use ($reservation) {
            return Reservation::query()->updateOrCreate(
                ['id' => $reservation->id],
                [
                    'student_id' => $reservation->student_id,
                    'schedule_id' => $reservation->schedule_id,
                    'start_time' => $reservation->start_time,
                    'end_time' => $reservation->end_time,
                    'reason' => $reservation->reason,
                    'teacher_evaluation' => $reservation->teacher_evaluation,
                    'evaluation_reason' => $reservation->evaluation_reason,
                    'attendance_flg' => $reservation->attendance_flg,
                    'attendance_time' => $reservation->attendance_time,
                ]
            );
        });
    }

    public function getListByTestId(int $test_id): array
    {
        return Question::query()
            ->where('test_id', $test_id)
            ->get()->toArray();
    }

    public function getModelAndSetParams(array $data, ?int $test_id): Question
    {
        if (isset($data['id'])) {
            $question = $this->find($data['id']);
        } else {
            $question = new Question();
        }
        $question->name = $data['name'];
        $question->test_id = $test_id;
        $question->content = $data['content'];
        $question->answer = $data['answer'];
        $question->image = $data['image'] ?? null;
        $question->choice1 = $data['choice1'] ?? null;
        $question->choice2 = $data['choice2'] ?? null;
        $question->choice3 = $data['choice3'] ?? null;
        $question->choice4 = $data['choice4'] ?? null;
        return $question;
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id): bool
    {
        $target = Reservation::query()->find($id);
        if ($target === null) {
            throw new \Exception('削除対象のデータが存在しません');
        }
        return $target->delete();
    }

    public function getReserveCount($schedule_id): ?int
    {
        return Reservation::query()
            ->where('schedule_id', $schedule_id)->count();
    }

    /**
     * @param int $schedule_id
     * @param int $student_id
     * @return Reservation|null
     */
    public function getStudentReserve(int $schedule_id, int $student_id): ?Reservation
    {
        return Reservation::query()
            ->where('schedule_id', $schedule_id)
            ->where('student_id', $student_id)
            ->first();
    }

    public function getAttendanceCount(int $schedule_id): int
    {
        return Reservation::query()
            ->where('schedule_id', $schedule_id)
            ->where('attendance_flg', 1)
            ->count();
    }

    public function getAttendanceCountByDate(int $student_id, string $date): int
    {
        return Reservation::query()
            ->join('schedules', 'schedules.id', 'reservations.schedule_id')
            ->where('reservations.student_id', $student_id)
            ->where('reservations.attendance_flg', 1)
            ->whereDate('schedules.date', '<=', $date)
            ->count();
    }

    public function getTotalStudentReservedCountToToday(int $student_id): int
    {
        $today = Carbon::today()->format('Y-m-d');
        return Reservation::query()
            ->join('schedules', function (JoinClause $join) use ($today) {
                $join->on('schedules.id', 'reservations.schedule_id');
            })
            ->where('student_id', $student_id)
            ->where('attendance_flg', 0)
            ->whereDate('schedules.date', '<=', $today)
            ->whereNull('reservations.deleted_at')
            ->count();
    }

    public function getTotalStudentReservedCountToDate(int $student_id, string $date): int
    {
        return Reservation::query()
            ->join('schedules', function (JoinClause $join) use ($date) {
                $join->on('schedules.id', 'reservations.schedule_id');
            })
            ->where('student_id', $student_id)
            ->where('attendance_flg', 0)
            ->whereDate('schedules.date', '<=', $date)
            ->whereNull('reservations.deleted_at')
            ->count();
    }

    public function getTotalStudentReserveCount(int $student_id): int
    {
        return Reservation::query()
            ->where('student_id', $student_id)
            ->where('attendance_flg', 1)
            ->count();
    }

    public function getTotalStudentReserveCountToDate(int $student_id, string $date): int
    {
        return Reservation::query()
            ->join('schedules', function (JoinClause $join) use ($date) {
                $join->on('schedules.id', 'reservations.schedule_id');
            })
            ->where('student_id', $student_id)
            ->where('attendance_flg', 1)
            ->whereDate('schedules.date', '<=', $date)
            ->count();
    }

    public function getTotalStudentReserveCountWithTeacherEvaluation(int $student_id): int
    {
        return Reservation::query()
            ->where('student_id', $student_id)
            ->where('attendance_flg', 1)
            ->where('teacher_evaluation', '<>', null)
            ->count();
    }

    public function getTotalStudentReserveCountWithTeacherEvaluationToDate(int $student_id, string $date): int
    {
        return Reservation::query()
            ->join('schedules', function (JoinClause $join) use ($date) {
                $join->on('schedules.id', 'reservations.schedule_id');
            })
            ->where('student_id', $student_id)
            ->where('attendance_flg', 1)
            ->where('teacher_evaluation', '<>', null)
            ->whereDate('schedules.date', '<=', $date)
            ->count();
    }

    /**
     * 予約一覧取得
     * @param $student_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getStudentReservations($student_id)
    {
        $params = request()->query();
        $ret = Reservation::query()
            ->select(['reservations.*', 'schedules.date'])
            ->join('schedules', 'schedules.id', 'reservations.schedule_id')
            ->where('reservations.student_id', $student_id);
        if (isset($params['start'])) {
            $ret->whereDate('schedules.date', '>=', $params['start']);
        }
        if (isset($params['end'])) {
            $ret->whereDate('schedules.date', '<=', $params['end']);
        }
        return $ret->get();
    }

    /**
     * 最終出席日取得
     * @param $student_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getLastAttendedDayByStudentId($student_id)
    {
        $ret = Reservation::query()
            ->select(['reservations.*', 'schedules.date'])
            ->join('schedules', 'schedules.id', 'reservations.schedule_id')
            ->where('reservations.student_id', $student_id)
            ->where('reservations.attendance_flg', 1)
            ->orderBy('schedules.date', 'desc');
        return $ret->first();
    }

    public function getReservationByStudentAndDate(int $student_id, string $date): ?Reservation
    {
        return Reservation::query()
            ->where('student_id', $student_id)
            ->join('schedules', function (JoinClause $join) use ($date) {
                $join->on('schedules.id', 'reservations.schedule_id');
            })
            ->whereDate('schedules.date', $date)
            ->first('reservations.*');
    }

    /**
     * @param int $student_id
     * @param string|null $date
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getReserveAndReportByStudentIdAndDate(int $student_id, string $date = null)
    {
        if ($date === null) {
            $date = Carbon::today()->format('Y-m-d');
        }
        return Reservation::query()
            ->select([
                'reservations.*',
                'reports.id as report_id',
                'personal_evaluation',
                'worked',
                'note',
            ])
            ->join('schedules', 'schedules.id', 'reservations.schedule_id')
            ->leftJoin('reports', 'reports.reservation_id', 'reservations.id')
            ->whereDate('schedules.date', $date)
            ->where('reservations.student_id', $student_id)
            ->first();
    }

    /**
     * @param int $student_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getTeacherEvaluations(int $student_id, string $date = null)
    {
        $ret = Reservation::query()
            ->select([
                'reservations.teacher_evaluation as evaluation',
                'reservations.evaluation_reason as reason',
                'schedules.date'
            ])
            ->join('schedules', 'schedules.id', 'reservations.schedule_id')
            ->where('reservations.student_id', $student_id)
            ->whereNotNull('reservations.teacher_evaluation');
        if (isset($date)) {
            $ret->whereDate('schedules.date', '<=', $date);
        }
        return $ret->get();
    }

    public function getTeacherEvaluationCount(int $student_id, string $date): int
    {
        return Reservation::query()
            ->join('schedules', 'schedules.id', 'reservations.schedule_id')
            ->where('reservations.student_id', $student_id)
            ->whereNotNull('reservations.teacher_evaluation')
            ->whereDate('schedules.date', '<=', $date)
            ->count();
    }

    /**
     * スケジュールIDから予約している受講者データを取得
     *
     * @param int $schedule_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getReservedStudentIds(int $schedule_id)
    {
        return Reservation::query()
            ->select([
                'reservations.student_id',
                'students.email'
            ])
            ->leftjoin('students', 'students.id', 'reservations.student_id')
            ->where('reservations.schedule_id', $schedule_id)
            ->get();
    }
}
