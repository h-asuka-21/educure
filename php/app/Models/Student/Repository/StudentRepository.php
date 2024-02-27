<?php


namespace App\Models\Student\Repository;

use App\Models\AbstractRepository;
use App\Models\Student\Student;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class StudentRepository extends AbstractRepository implements StudentRepositoryInterface
{
    protected array $searchable = [
        'students.name' => 'like',
        'students.email' => 'like',
        'students.course_id' => '=',
        'students.start_date' => 'date',
        'students.after_graduation_flg' => '=',
        'reservations.attendance_flg' => 'true'
    ];

    protected string $tbl_name = 'students';

    protected array $sortable = [
        'name' => 'students.name',
        'course_name' => 'students.course_id',
        'start_date' => 'students.start_date',
        'end_date' => 'students.end_date',
        'after_graduation_flg' => 'students.after_graduation_flg',
    ];

    /**
     * @param int|null $company_id
     * @param bool $paginate
     * @return array|Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getList(?int $company_id, bool $paginate = true, int $type = null, bool $end_this_month = false, bool $without_old_course_id = false)
    {
        $query = request()->query();
        $result = Student::query()
            ->select([
                'students.*',
                DB::raw('ifnull(courses.name,"未設定") as course_name'),
            ])
            ->where(function (Builder $builder) use ($query) {
                $this->search($builder, $query);
            })
            ->leftJoin('courses', 'courses.id', 'students.course_id');
        if ($company_id !== null) {
            $result->where('company_id', $company_id);
        }
        if ($end_this_month) {
            $now = Carbon::now();
            $result->whereDate('end_date', '>=', $now->format('Y-m-01'))
                ->whereDate('end_date', '<=', $now->format('Y-m-t'));
        }
        if (!is_null($type)) {
            if ($type === 1) {
                // 受講中
                $result->where('after_graduation_flg', 0);
            } elseif ($type === 2) {
                // 卒業
                $result->where('after_graduation_flg', '<>', 0)
                    ->where('after_graduation_flg', '<>', 4);
            } elseif ($type === 3) {
                // リタイア
                $result->where('after_graduation_flg', 4);
            }
        }
        if ($without_old_course_id) {
            $result->where('course_id', '<>', 4);
        }
        $result->orderByRaw($this->setOrder());
        if ($paginate) {
            return $result
                ->paginate($this->getPerPage($query))
                ->toArray();
        }
        return $result->get();
    }

    public function getListByEndDate(?int $company_id, int $type = null, $end_month, bool $without_old_course_id = false)
    {
        $query = request()->query();
        $result = Student::query()
            ->select([
                'students.*',
                DB::raw('ifnull(courses.name,"未設定") as course_name'),
            ])
            ->where(function (Builder $builder) use ($query) {
                $this->search($builder, $query);
            })
            ->leftJoin('courses', 'courses.id', 'students.course_id');
        if ($company_id !== null) {
            $result->where('company_id', $company_id);
        }
        if ($end_month) {
            $date = new Carbon($end_month);
            $result->whereDate('end_date', '>=', $date->format('Y-m-01'))
                ->whereDate('end_date', '<=', $date->format('Y-m-t'));
        }
        if (!is_null($type)) {
            if ($type === 1) {
                // 受講中
                $result->where('after_graduation_flg', 0);
            } elseif ($type === 2) {
                // 卒業
                $result->where('after_graduation_flg', '<>', 0)
                    ->where('after_graduation_flg', '<>', 4);
            } elseif ($type === 3) {
                // リタイア
                $result->where('after_graduation_flg', 4);
            }
        }
        if ($without_old_course_id) {
            $result->where('course_id', '<>', 4);
        }
        $result->orderByRaw($this->setOrder());
        return $result->get();
    }

    public function getListWithCompanyAttribute(int $type = null, bool $without_old_course_id = false)
    {
        $result = Student::query()
            ->select([
                'students.id',
                'companies.industry',
                'companies.number_of_employees',
                'companies.year_of_establishment',
                'companies.average_age',
            ])
            ->join('companies', 'companies.id', 'students.company_id');
        if (!is_null($type)) {
            if ($type === 1) {
                // 受講中
                $result->where('after_graduation_flg', 0);
            } elseif ($type === 2) {
                // 卒業
                $result->where('after_graduation_flg', '<>', 0)
                    ->where('after_graduation_flg', '<>', 4);
            } elseif ($type === 3) {
                // リタイア
                $result->where('after_graduation_flg', 4);
            }
        }
        if ($without_old_course_id) {
            $result->where('course_id', '<>', 4);
        }
        $result->orderByRaw($this->setOrder());
        return $result->get();
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return Student::query()->find($id);
    }

    /**
     * @param Student $student
     * @return bool
     */
    public function save(Student $student)
    {
        return DB::transaction(function () use ($student) {
            try {
                return Student::query()->updateOrCreate(
                    ['id' => $student->id],
                    [
                        'company_id' => $student->company_id,
                        'name' => $student->name,
                        'name_kana' => $student->name_kana,
                        'email' => $student->email,
                        'password' => $student->password,
                        'course_id' => $student->course_id,
                        'start_date' => $student->start_date,
                        'end_date' => $student->end_date,
                        'birthday' => $student->birthday,
                        'after_graduation_flg' => $student->after_graduation_flg,
                    ]
                );
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return false;
            }
        });
    }

    public function updateforScores(Student $student)
    {
        return DB::transaction(function () use ($student) {
            try {
                return Student::query()->where('id', $student->id)->update(
                    [
                        'total_score' => $student->total_score,
                        'teacher_score' => $student->teacher_score,
                        'sales_score' => $student->sales_score,
                        'comprehension_score' => $student->comprehension_score,
                        'think_score' => $student->think_score,
                        'attendance_score' => $student->attendance_score,
                        'report_score' => $student->report_score,
                        'progress_score' => $student->progress_score,
                        'aggregate_date' => $student->aggregate_date,
                    ]
                );
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return false;
            }
        });
    }

    /**
     * @param int $company_id
     * @return int
     */
    public function getCountByCompanyId(int $company_id)
    {
        return Student::query()
            ->where('company_id', $company_id)
            ->count();
    }

    /**
     * @param int $company_id
     * @return int
     */
    public function getTakingCountByCompanyId(int $company_id)
    {
        return Student::query()
            ->where('company_id', $company_id)
            ->where('after_graduation_flg', 0)
            ->count();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return Student::find($id)->delete();
    }

    public function getReserveStudentList(int $schedule_id, bool $paginate = true): array
    {
        $query = request()->query();
        $ret = Student::query()
            ->select([
                'students.*',
                'reservations.start_time',
                'reservations.end_time',
                'reservations.created_at as reserve_date',
                'reservations.attendance_flg'
            ])
            ->join('reservations', function (JoinClause $join) use ($schedule_id) {
                $join->on('reservations.student_id', 'students.id')
                    ->where('reservations.schedule_id', $schedule_id)
                    ->whereNull('reservations.deleted_at');
            })
            ->where(function (Builder $builder) use ($query) {
                $this->search($builder, $query);
            })
            ->whereNull('students.deleted_at')
            ->orderBy('reserve_date', 'desc');
        if ($paginate) {
            return $ret->paginate($this->getPerPage($query))->toArray();
        }
        return $ret->get()->toArray();
    }

    /**
     * @param $company_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getStudentsAndTodayReservations(?int $company_id)
    {
        $params = request()->query();
        $today = Carbon::now()->format('Y-m-d');
        $selects = [
            'students.*',
            DB::raw("max(`reservations`.`attendance_flg`) as `attendance_flg`"),
        ];
        if ($company_id === null) {
            $selects[] = 'companies.name as company_name';
        }
        $limit = 15;
        $ret = Student::query()
            ->select($selects)
            ->leftJoin('reservations', function (JoinClause $join) use ($today) {
                $join->on('reservations.student_id', 'students.id')
                    ->join('schedules', 'schedules.id', 'reservations.schedule_id')
                    ->whereDate('schedules.date', $today)
                    ->whereNull('reservations.deleted_at');
            })
            ->groupBy('students.id')
            ->orderBy('students.start_date', 'desc');
        if (!isset($params['curriculum_id'])) {
            // カリキュラム検索のときは無限ロードをオフにする
            $ret->limit($limit);
            if ($params['offset'] > 0) {
                $ret->offset($limit * $params['offset']);
            }
        }
        if ($company_id !== null) {
            $ret->where('students.company_id', $company_id);
        } else {
            $ret->join('companies', 'companies.id', 'students.company_id');
        }
        if (isset($params['attendance_reserve_flg'])) {
            if ($params['attendance_reserve_flg'] === '0') {
                $ret->where('attendance_flg', 1);
            } elseif ($params['attendance_reserve_flg'] === '1') {
                $ret->where('attendance_flg', 0);
            }
        }
        if (isset($params['name']) && !empty($params['name'])) {
            $ret->where('students.name', 'LIKE', "%{$params['name']}%");
        }
        if (isset($params['after_graduation_flg'])) {
            $ret->where('students.after_graduation_flg', (int)$params['after_graduation_flg']);
        }
        if (isset($params['start_month'])) {
            $ret->where('students.start_date', 'LIKE', "{$params['start_month']}%");
        }

        return $ret->get();
    }

    /**
     * @param \Carbon\Carbon $target_date
     * @param int|null $company_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getReservedStudentByDate(\Carbon\Carbon $target_date, ?int $company_id)
    {
        $ret = Student::query()
            ->select([
                'students.*',
                'companies.name as company_name',
                'reservations.start_time',
                'reservations.end_time',
                'reservations.attendance_flg',
            ])
            ->join('companies', 'companies.id', 'students.company_id')
            ->join('reservations', function (JoinClause $joinClause) {
                $joinClause->on('reservations.student_id', 'students.id')
                    ->whereNull('reservations.deleted_at');
            })
            ->join('schedules', function (JoinClause $join) use ($target_date) {
                $join->on('schedules.id', 'reservations.schedule_id')
                    ->whereDate('schedules.date', $target_date->format('Y-m-d'));
            })->orderBy('reservations.created_at', 'desc');
        if ($company_id) {
            $ret->where('students.company_id', $company_id);
        }
        return $ret->get();
    }

    public function getStudentsCountWithCompanyIdAndAttribute(?int $company_id, ?int $type, string $column, ?int $select)
    {
        $result = Student::query();
        if ($company_id !== null) {
            $result->where('company_id', $company_id);
        }
        if ($type !== null) {
            if ($type === 1) {
                // 受講中
                $result->where('after_graduation_flg', 0);
            } elseif ($type === 2) {
                // 卒業
                $result->where('after_graduation_flg', '<>', 0)
                    ->where('after_graduation_flg', '<>', 4);
            } elseif ($type === 3) {
                // リタイア
                $result->where('after_graduation_flg', 4);
            }
        }
        $result->where($column, $select);
        return $result->count();
    }

    public function getStudentsCountWithCompanyAttribute(?int $type, string $column, ?int $select)
    {
        $result = Student::query()
            ->select([
                'students.id',
                'companies.industry',
                'companies.number_of_employees',
                'companies.year_of_establishment',
                'companies.average_age',
            ])
            ->join('companies', 'companies.id', 'students.company_id');
        if ($type !== null) {
            if ($type === 1) {
                // 受講中
                $result->where('after_graduation_flg', 0);
            } elseif ($type === 2) {
                // 卒業
                $result->where('after_graduation_flg', '<>', 0)
                    ->where('after_graduation_flg', '<>', 4);
            } elseif ($type === 3) {
                // リタイア
                $result->where('after_graduation_flg', 4);
            }
        }
        $result->where($column, $select);
        return $result->count();
    }

}
