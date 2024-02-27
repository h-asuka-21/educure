<?php


namespace App\Models\Schedule\Repository;

use App\Models\Schedule\Schedule;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

interface ScheduleRepositoryInterface
{
    public function getList(?int $schedule_id): array;

    /**
     * @param int $id
     * @return Schedule|null
     */
    public function find(int $id);


    /**
     * @param Schedule $schedule
     * @return bool|Schedule
     */
    public function save(Schedule $schedule);

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @return int
     */
    public function lastInsertId(): int;

    public function getMonthlySchedule(?CarbonInterface $dateObj, int $company_id = null);

    /**
     * 日付からスケジュールを取得
     * @param string $date
     * @param int $company_id
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getScheduleFromDate(string $date, int $company_id);

}
