<?php


namespace App\Models\Report\Repository;

use App\Models\AbstractRepository;
use App\Models\Report\Report;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class ReportRepository extends AbstractRepository implements ReportRepositoryInterface
{
    protected array $searchable = [
        'reports.name' => 'like',
        'reports.created_at' => 'like',
        'reports.worked' => 'like',
    ];

    /**
     * @param int $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        $query = request()->query();
        return Report::query()
            ->select([
                'reports.*',
                'students.name',
            ])
            ->where(function (Builder $builder) use ($query) {
                $this->search($builder, $query);
            })
            ->leftJoin('students', 'students.id', 'reports.student_id')
            ->where('company_id', $company_id)
            ->paginate($this->getPerPage($query))
            ->toArray();
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return Report::query()->find($id);
    }

    public function getAttendanceCount(int $schedule_id): int
    {
        return Report::query()
            ->join('reservations', function (JoinClause $joinClause) {
                $joinClause->on('reservations.id', 'reports.reservation_id')
                    ->whereNull('reservations.deleted_at');
            })
            ->groupBy('reports.student_id')
            ->where('reservations.schedule_id', $schedule_id)
            ->count();
    }

    /**
     * @param int $id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getReportsByReservations(int $id)
    {
        return Report::query()
            ->where('reservation_id', $id)
            ->get();
    }

    /**
     * @param int $student_id
     * @param bool $paginate
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    
    public function getListByStudentId(int $student_id, bool $paginate)
    {
        $query = request()->query();
        $ret = Report::query()
            ->select([
                'reports.*',
                'schedules.date as date',
                'reservations.attendance_time as attendance_time'
            ])
            ->join('reservations', function (JoinClause $joinClause) {
                $joinClause->on('reservations.id', 'reports.reservation_id')
                    ->whereNull('reservations.deleted_at');
            })
            ->join('schedules', 'schedules.id', 'reservations.schedule_id')
            ->where(function (Builder $builder) use ($query) {
                $this->search($builder, $query);
            })
            ->where('reports.student_id', $student_id)
            // care-study時代の出席データは日報が移管されていないので、運用開始以降に登録されたスケジュールのみに絞る
            ->whereDate('schedules.date', '>=', '2020-10-13')
            ->orderBy('schedules.date', 'desc');
        if ($paginate) {
            return $ret->paginate($this->getPerPage($query));
        }
        return $ret->get();
    }

    public function getLowEvaluationListByStudentId(int $student_id)
    {
        $subMonth = Carbon::now()->subMonth()->format('Y-m-d');
        $ret = Report::query()
            ->select([
                'reports.*',
                'schedules.date as date'
            ])
            ->join('reservations', function (JoinClause $joinClause) {
                $joinClause->on('reservations.id', 'reports.reservation_id')
                    ->whereNull('reservations.deleted_at');
            })
            ->join('schedules', 'schedules.id', 'reservations.schedule_id')
            ->where('reports.student_id', $student_id)
            ->whereDate('schedules.date', '>=', $subMonth)
            ->orderBy('schedules.date', 'desc');
        return $ret->get();
    }

    public function getAvgEvaluationAndAttendanceCountForOneMonthByStudentId(int $student_id)
    {
        $subMonth = Carbon::today()->subMonth()->format('Y-m-d');
        $ret = Report::query()
            ->select([
                DB::raw("count(rpt.id) as count"),
                DB::raw("avg(rpt.personal_evaluation) as avg_evaluation")
            ])
            ->from(function (\Illuminate\Database\Query\Builder $builder) use ($student_id, $subMonth) {
                $builder
                    ->select([
                        'reports.*',
                        'schedules.date as date',
                    ])
                    ->from('reports')
                    ->join('reservations', function (JoinClause $joinClause) {
                        $joinClause->on('reservations.id', 'reports.reservation_id')
                            ->whereNull('reservations.deleted_at');
                    })
                    ->join('schedules', 'schedules.id', 'reservations.schedule_id')
                    ->where('reports.student_id', $student_id)
                    ->whereDate('schedules.date', '>=', $subMonth)
                    ->orderBy('schedules.date', 'desc');
            }, 'rpt');
        return $ret->first();
    }

}
