<?php


namespace App\Models\TeacherSchedule\Repository;

use App\Models\AbstractRepository;
use App\Models\TeacherSchedule\TeacherSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class TeacherScheduleRepository extends AbstractRepository implements TeacherScheduleRepositoryInterface
{
    protected array $searchable = [
        'teacher_schedules.schedule_id' => 'like',
        'teacher_schedules.user_id' => 'like',
    ];

    /**
     * @param int $schedule_id
     * @return array
     */
    public function getList(?int $schedule_id): array
    {
        $query = request()->query();
        $result = TeacherSchedule::query();
        if (!empty($schedule_id)) {
            $result->where('schedule_id', $schedule_id);
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
        return TeacherSchedule::query()->find($id);
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function deleteByScheduleId(int $schedule_id)
    {
        return TeacherSchedule::query()
            ->where('schedule_id', $schedule_id)
            ->delete();
    }


    /**
     * @param TeacherSchedule $teacher_schedule
     * @return bool
     */
    public function save(TeacherSchedule $teacher_schedule): bool
    {
        return DB::transaction(function () use ($teacher_schedule) {
            TeacherSchedule::updateOrCreate(
                ['id' => $teacher_schedule->id],
                [
                    'schedule_id' => $teacher_schedule->schedule_id,
                    'user_id' => $teacher_schedule->user_id,
                ]
            );
            return true;
        });
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return TeacherSchedule::find($id)->delete();
    }

    public function getUsersByScheduleId(int $schedule_id)
    {
        return TeacherSchedule::query()
            ->select([
                'users.id as id',
                'users.email',
                'users.name as name',
            ])
            ->where('schedule_id', $schedule_id)
            ->join('users', 'users.id', 'teacher_schedules.user_id')
            ->get();
    }

}
