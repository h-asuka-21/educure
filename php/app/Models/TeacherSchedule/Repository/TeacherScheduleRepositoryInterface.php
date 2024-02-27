<?php


namespace App\Models\TeacherSchedule\Repository;

use App\Models\TeacherSchedule\TeacherSchedule;

interface TeacherScheduleRepositoryInterface
{
    public function getList(?int $schedule_id): array;

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id);

    public function deleteByScheduleId(int $schedule_id);


    /**
     * @param TeacherSchedule $schedule
     * @return bool
     */
    public function save(TeacherSchedule $schedule): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    public function getUsersByScheduleId(int $schedule_id);

}
