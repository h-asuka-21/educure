<?php


namespace App\Models\Schedule\Repository;

use App\Models\AbstractRepository;
use App\Models\Schedule\Schedule;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class ScheduleRepository extends AbstractRepository implements ScheduleRepositoryInterface
{
    protected array $searchable = [
        'schedules.name' => 'like',
        'schedules.date' => 'like',
        'schedules.start_time' => 'like',
        'schedules.end_time' => 'like',
        'schedules.available_limit' => 'like',
    ];

    /**
     * @param int $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        $query = request()->query();
        $result = Schedule::query();
        if (!empty($company_id)) {
            $result->where('company_id', $company_id);
        }
        return $result
            ->where(function (Builder $builder) use ($query) {
                $this->search($builder, $query);
            })
            ->paginate($this->getPerPage($query))
            ->toArray();
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return Schedule::query()->find($id);
    }


    /**
     * @param Schedule $schedule
     * @return bool|Schedule
     */
    public function save(Schedule $schedule)
    {
        return DB::transaction(function () use ($schedule) {
            return Schedule::query()->updateOrCreate(
                ['id' => $schedule->id],
                [
                    'name' => $schedule->name,
                    'company_id' => $schedule->company_id,
                    'date' => $schedule->date,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'available_limit' => $schedule->available_limit,
                ]
            );
        });
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return Schedule::find($id)->delete();
    }

    public function lastInsertId(): int
    {
        return DB::getPdo()->lastInsertId();
    }

    /**
     * @param CarbonInterface|null $dateObj
     * @param int|null $company_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getMonthlySchedule(?CarbonInterface $dateObj, int $company_id = null)
    {
        $from = Carbon::make($dateObj)->startOf('week')->subDay();
        $to = Carbon::make($dateObj)->endOfMonth()->endOfWeek();
        $result = Schedule::query()->select(
            [
                'schedules.id',
                'schedules.name',
                'schedules.available_limit as student_count',
                DB::raw('concat(concat(schedules.date," "),schedules.start_time) as start'),
                DB::raw('concat(concat(schedules.date," "),schedules.end_time) as end'),
                'companies.name as company_name',
                'schedules.company_id'
            ]
        )->join('companies', 'companies.id', 'schedules.company_id')
            ->whereBetween(
                'date', [
                    $from->format('Y-m-d 00:00:00')
                    , $to->format('Y-m-d 23:59:59')
                ]
            )
            ->orderBy('date');
        if ($company_id !== null) {
            $result->where('company_id', $company_id);
        }
        return $result
            ->get();
    }

    /**
     * 日付からスケジュールを取得
     * @param string $date
     * @param int $company_id
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getScheduleFromDate(string $date, int $company_id)
    {
        return Schedule::query()
            ->where('date', $date)
            ->where('company_id', $company_id)
            ->first();
    }

}
