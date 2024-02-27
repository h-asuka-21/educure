<?php


namespace App\Models\Reservation\Repository;

use App\Models\Question\Question;
use App\Models\Reservation\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

interface ReservationRepositoryInterface
{
    public function getList(?int $company_id): array;

    /**
     * @param int $id
     * @return Reservation|null
     */
    public function find(int $id);


    /**
     * @param Reservation $reservation
     * @return Reservation|false
     */
    public function save(Reservation $reservation);


    public function getListByTestId(int $test_id): array;

    public function getModelAndSetParams(array $data, ?int $test_id): Question;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    public function getReserveCount($schedule_id): ?int;

    /**
     * @param int $schedule_id
     * @param int $student_id
     * @return Reservation|null
     */
    public function getStudentReserve(int $schedule_id, int $student_id): ?Reservation;

    public function getAttendanceCount(int $schedule_id): int;

    public function getAttendanceCountByDate(int $student_id, string $date): int;

    public function getTotalStudentReservedCountToToday(int $student_id): int;

    public function getTotalStudentReservedCountToDate(int $student_id, string $date): int;

    public function getTotalStudentReserveCount(int $student_id): int;

    public function getTotalStudentReserveCountToDate(int $student_id, string $date): int;

    public function getTotalStudentReserveCountWithTeacherEvaluation(int $student_id): int;

    public function getTotalStudentReserveCountWithTeacherEvaluationToDate(int $student_id, string $date): int;

    /**
     * 生徒ごとの予約一覧取得
     * @param $student_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getStudentReservations($student_id);

    /**
     * 最終出席日取得
     * @param $student_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getLastAttendedDayByStudentId($student_id);

    /**
     * @param int $student_id
     * @param string $date
     * @return Reservation|null
     */
    public function getReservationByStudentAndDate(int $student_id, string $date): ?Reservation;

    /**
     * 生徒IDと日付から、予約データと日報データを取得する（日付がnullなら今日の日報）
     * @param int $student_id
     * @param string|null $date
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getReserveAndReportByStudentIdAndDate(int $student_id, string $date = null);

    /**
     * @param int $student_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getTeacherEvaluations(int $student_id, string $date = null);

    public function getTeacherEvaluationCount(int $student_id, string $date): int;

    public function getReservedStudentIds(int $schedule_id);
}
