<?php


namespace App\Models\Report\Repository;

use App\Models\Report\Report;
use Illuminate\Database\Query\Builder;

interface ReportRepositoryInterface
{
    public function getList(?int $report_id): array;

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id);

    public function getAttendanceCount(int $schedule_id): int;

    /**
     * @param int $id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getReportsByReservations(int $id);

    /**
     * @param int $student_id
     * @param bool $paginate
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByStudentId(int $student_id, bool $paginate);

    public function getLowEvaluationListByStudentId(int $student_id);

    public function getAvgEvaluationAndAttendanceCountForOneMonthByStudentId(int $student_id);
}