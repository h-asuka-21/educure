<?php


namespace App\Models\Progress\Repository;

use App\Models\AbstractRepository;
use App\Models\Company\Company;
use App\Models\Progress\Progress;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class ProgressRepository extends AbstractRepository implements ProgressRepositoryInterface
{
    protected array $searchable = [
        'companies.company_code' => 'like',
        'companies.name' => 'like',
    ];

    /**
     * @param int $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        $query = request()->query();
        $result = Company::query();
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
        return Progress::query()->find($id);
    }


    /**
     * @param Progress $progress
     * @return Progress
     */
    public function save(Progress $progress)
    {
        return DB::transaction(function () use ($progress) {
            return Progress::updateOrCreate(
                ['id' => $progress->id],
                [
                    'student_id' => $progress->student_id,
                    'step_id' => $progress->step_id,
                    'progress_status' => $progress->progress_status,
                    'reservation_id' => $progress->reservation_id,
                    'application_flg' => $progress->application_flg,
                    'date' => $progress->date,
                ]
            );
        });
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id): bool
    {
        $target = $this->find($id);
        if ($target === null) {
            throw new \Exception('削除対象が見つかりませんでした', 500);
        }
        return $target->delete();
    }

    public function getCurriculumDateCount(int $student_id, int $curriculum_id): ?int
    {
        return Progress::query()
            ->join('steps', function (JoinClause $join) use ($curriculum_id) {
                $join->on('steps.id', 'progresses.step_id')
                    ->where('steps.curriculum_id', $curriculum_id);
            })
            ->where('progresses.student_id', $student_id)
            ->where('steps.curriculum_id', $curriculum_id)
            ->count(DB::raw('distinct progresses.date'));
    }

    /**
     * @param int $curriculum_id
     * @return int|float|string|null
     */
    public function getCurriculumAverage(int $curriculum_id)
    {
        $result = Progress::withTrashed()
            ->select([
                DB::raw('AVG(prg.count) as count')
            ])
            ->from(function (\Illuminate\Database\Query\Builder $builder) use ($curriculum_id) {
                $builder
                    ->select(
                        DB::raw('count(distinct progresses.date) as count')
                    )
                    ->from('progresses')
                    ->join('steps', function (JoinClause $join) use ($curriculum_id) {
                        $join->on('steps.id', 'progresses.step_id')
                            ->where('steps.curriculum_id', $curriculum_id);
                    })
                    ->where('steps.curriculum_id', $curriculum_id)
                    ->groupBy('progresses.student_id');
            }, 'prg')
            ->first();
        if ($result !== null) {
            $result = $result->toArray();
            return round($result['count'], 1);
        }
        return null;
    }

    public function getStepAverage(int $step_id)
    {
        $result = Progress::withTrashed()
            ->select([
                DB::raw('AVG(prg.count) as count')
            ])
            ->from(function (\Illuminate\Database\Query\Builder $builder) use ($step_id) {
                $builder
                    ->select(
                        DB::raw('count(distinct progresses.date) as count')
                    )
                    ->from('progresses')
                    ->join('steps', 'steps.id', 'progresses.step_id')
                    ->where('steps.id', $step_id)
                    ->groupBy('progresses.student_id');
            }, 'prg')
            ->first();
        if ($result !== null) {
            $result = $result->toArray();
            return round($result['count'], 1);
        }
        return null;

    }

    /**
     * @param int $student_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getStudentProgresses(int $student_id, ?string $date = null)
    {
        $params = request()->query();
        $ret = Progress::query()
            ->select([
                'progresses.*',
                'reservations.start_time',
                'reservations.end_time',
                'reservations.reason',
                'reservations.evaluation_reason',
                'reservations.attendance_flg',
                'reservations.teacher_evaluation',
                'schedules.date',
                'reports.note',
                'reports.personal_evaluation',
                'reports.worked',
            ])
            ->join('reservations', function (JoinClause $joinClause) {
                $joinClause->on('reservations.id', 'progresses.reservation_id')
                    ->whereNull('reservations.deleted_at');
            })
            ->join('schedules', 'schedules.id', 'reservations.schedule_id')
            ->leftJoin('reports', 'reports.reservation_id', 'reservations.id');
        if ($date !== null) {
            $ret->whereDate('schedules.date', $date);
        }
        if (isset($params['start']) && $params['end']) {
            // 期間指定がある場合
            $ret->whereDate('schedules.date', '>=', $params['start'])
                ->whereDate('schedules.date', '<=', $params['end']);
        }
        return $ret->where('progresses.student_id', $student_id)
            ->get();
    }

    /**
     * @param int $reservation_id
     * @param bool $with_curriculum_name
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getProgressesByReservations(int $reservation_id, bool $with_curriculum_name = false, ?int $course_id = 0)
    {
        $selects = [
            'progresses.*',
            'curriculums.name as curriculum_name',
            'steps.name'
        ];
        $ret = Progress::query()
            ->select($selects)
            ->join('steps', 'steps.id', 'progresses.step_id')
            ->join('curriculums', 'curriculums.id', 'steps.curriculum_id')
            ->join('course_curriculums', 'curriculums.id', 'course_curriculums.curriculum_id')
            ->orderBy('progresses.id', 'desc')
            ->where('reservation_id', $reservation_id);
        if ($with_curriculum_name) {
            $selects[] = 'curriculums.name as curriculum_name';
        }
        if ($course_id) {
            $ret->where('course_curriculums.course_id', $course_id);
        }
        return $ret->get();
    }

    /**
     * @param $step_id
     * @param $student_id
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getLatestStatusByStepIdAndStudentId($step_id, $student_id)
    {
        return Progress::query()
            ->select([
                'progresses.id',
                // 'progresses.percent',
                'progresses.progress_status',
                // DB::raw('if(progresses.percent is null, "",concat("前回の進捗：",concat(progresses.percent,"％"))) as description'),
                // DB::raw('if(progresses.progress_status is null, "", contat("前回の進捗：", concat(progresses.progress_status,"％"))) as description'),
            ])
            ->join('steps', 'steps.id', 'progresses.step_id')
            ->join('curriculums', 'curriculums.id', 'steps.curriculum_id')
            ->join('course_curriculums', 'course_curriculums.curriculum_id', 'curriculums.id')
            ->where('step_id', $step_id)
            ->where('student_id', $student_id)
            ->whereNull('progresses.deleted_at')
            ->orderBy('course_curriculums.order')
            ->orderBy('steps.order')
            ->orderBy('progresses.date', 'desc')
            ->first();
    }

    /**
     * コース内のクリア済み（すべてのステップの進捗が100%）のカリキュラムを取得
     * @param int $course_id
     * @param int $student_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getCleared(int $course_id, int $student_id)
    {
        return Progress::query()
            ->select([
                'curriculums.*',
                DB::raw('count(DISTINCT steps.id) as progress_count'),
            ])
            ->join('steps', 'steps.id', 'progresses.step_id')
            ->join('curriculums', 'curriculums.id', 'steps.curriculum_id')
            ->join('course_curriculums', 'course_curriculums.curriculum_id', 'curriculums.id')
            ->where('progresses.student_id', $student_id)
            // ->where('progresses.percent', 100)
            ->where('progresses.progress_status', 3)
            ->whereNull('progresses.deleted_at')
            ->where('course_curriculums.course_id', $course_id)
            ->groupBy('course_curriculums.id')
            ->havingRaw('progress_count = (select count(s.id) from steps as s where curriculums.id = s.curriculum_id and s.deleted_at is null)')
            ->orderBy('course_curriculums.order')
            ->get();
    }


    /**
     * 評価集計バッチ用
     * ステップの進捗が100%になったデータを取得
     */
    public function getClearedStep(int $curriculum_id, int $student_id)
    {
        return Progress::query()
            ->select([
                'progresses.date',
                'progresses.step_id',
                'steps.curriculum_id'
            ])
            ->join('steps', 'steps.id', 'progresses.step_id')
            ->join('curriculums', 'curriculums.id', 'steps.curriculum_id')
            ->where('progresses.student_id', $student_id)
            // ->where('progresses.percent', 100)
            ->where('progresses.progress_status', 3)
            ->where('curriculums.id', $curriculum_id)
            ->whereNull('progresses.deleted_at')
            ->orderBy('steps.order', 'desc')
            ->first();
    }

    public function getPrgoressed(int $student_id)
    {
        $date = date("Y-m-d");


        return Progress::query()
            ->select([
                'progresses.*',
                'steps.name'
            ])
            ->join('steps', 'steps.id', 'progresses.step_id')
            ->join('curriculums', 'curriculums.id', 'steps.curriculum_id')
            ->where('progresses.student_id', $student_id)
            ->where('progresses.date', $date)
            ->whereNull('progresses.deleted_at')
            ->get();
    }

    /**
     * 本日申請中の進捗がある場合
     */
    public function getInProgress(int $student_id)
    {
        $date = date("Y-m-d");

        return Progress::query()
            ->select([
                'progresses.*',
                'steps.name'
            ])
            ->join('steps', 'steps.id', 'progresses.step_id')
            ->join('curriculums', 'curriculums.id', 'steps.curriculum_id')
            ->where('progresses.student_id', $student_id)
            ->where('progresses.progress_status', '<>', 3)
            ->where('progresses.date', $date)
            ->whereNull('progresses.deleted_at')
            ->first();
    }


    public function getClearedStepWithStudentId(int $student_id)
    {
        return Progress::query()
            ->select([
                'progresses.step_id',
            ])
            ->where('progresses.student_id', $student_id)
            ->where('progresses.progress_status', 3)
            ->whereNull('progresses.deleted_at')
            ->orderBy('progresses.date', 'desc')
            ->orderBy('progresses.step_id', 'desc')
            ->first();
    }
}
