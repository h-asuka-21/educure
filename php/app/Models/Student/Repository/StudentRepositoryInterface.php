<?php


namespace App\Models\Student\Repository;

use App\Models\Student\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

interface StudentRepositoryInterface
{
    /**
     * @param int|null $company_id
     * @param bool $paginate
     * @param null $type
     * @param bool $end_this_month
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    public function getList(?int $company_id, bool $paginate = true, int $type = null, bool $end_this_month = false, bool $without_old_course_id = false);

    public function getListByEndDate(?int $company_id, int $type = null, $end_month, bool $without_old_course_id = false);

    public function getListWithCompanyAttribute(int $type = null, bool $without_old_course_id = false);

    /**
     * @param int $id
     * @return Student|null
     */
    public function find(int $id);

    /**
     * @param Student $student
     * @return bool|Student
     */
    public function save(Student $student);

    /**
     * Undocumented function
     *
     * @param Student $student
     * @return void
     */
    public function updateforScores(Student $student);

    /**
     * @param int $company_id
     * @return mixed
     */
    public function getCountByCompanyId(int $company_id);

    /**
     * @param int $company_id
     * @return mixed
     */
    public function getTakingCountByCompanyId(int $company_id);

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @param int $schedule_id
     * @param bool $paginate
     * @return array
     */
    public function getReserveStudentList(int $schedule_id, bool $paginate = true): array;

    /**
     * @param $company_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getStudentsAndTodayReservations(?int $company_id);

    /**
     * @param \Carbon\Carbon $target_date
     * @param int|null $company_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getReservedStudentByDate(Carbon $target_date, ?int $company_id);

    public function getStudentsCountWithCompanyIdAndAttribute(?int $company_id, ?int $type, string $column, ?int $select);

    public function getStudentsCountWithCompanyAttribute(?int $type, string $column, ?int $select);

}
