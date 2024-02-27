<?php


namespace App\Models\Step\Repository;

use App\Models\AbstractRepository;
use App\Models\Step\Step;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class StepRepository extends AbstractRepository implements StepRepositoryInterface
{

    protected array $searchable = [
        'steps.name' => 'like',
        'steps.target_days' => '=',
        'steps.deadline_days' => '=',
    ];

    /**
     * @param int $curriculum_id
     * @return array
     */
    public function getList(?int $curriculum_id): array
    {
        $query = request()->query();
        $result = Step::query();
        if (!empty($curriculum_id)) {
            $result->where('curriculum_id', $curriculum_id);
        }
        return $result
            ->where(function (Builder $builder) use ($query) {
                $this->search($builder, $query);
            })
            ->orderBy('order')
            ->paginate($this->getPerPage($query))
            ->toArray();
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return Step::query()->find($id);
    }

    public function save(Step $step)
    {
        try {
            return Step::query()->updateOrCreate(
                ['id' => $step->id],
                [
                    'curriculum_id' => $step->curriculum_id,
                    'name' => $step->name,
                    'content' => $step->content,
                    'image' => $step->image,
                    'target_days' => $step->target_days,
                    'deadline_days' => $step->deadline_days,
                    'order' => $step->order,

                ]
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $target = Step::query()->find($id);
            if ($target === null) {
                return false;
            }
            return $target->delete();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function getAutocomplete(int $company_id)
    {
        $params = request()->query();
        $selects = [
            DB::raw('steps.name as text'),
            'steps.id as value',
            'curriculums.name as description'
        ];
        $ret = Step::query()
            ->select($selects)
            ->join('curriculums', 'curriculums.id', 'steps.curriculum_id')
            ->leftJoin('course_curriculums', 'course_curriculums.curriculum_id', 'curriculums.id')
            ->leftJoin('courses', 'courses.id', 'course_curriculums.course_id');
        if (isset($params['student_id'])) {
            $ret->leftJoin('students', 'courses.id', 'students.course_id')
                ->where('students.id', $params['student_id']);
        } else {
            $ret->leftJoin('course_groups', 'course_groups.course_id', 'courses.id');
            $ret->where(function (Builder $builder) use ($company_id, $params) {
                if (isset($params['course_id']) && !empty($params['course_id'])) {
                    $builder->where('courses.id', $params['course_id']);
                } else {
                    $builder->where('course_groups.company_id', $company_id);
                }
            });
        }
        return $ret->orderBy('course_curriculums.order')
            ->orderBy('steps.order')
            ->get();
    }

    public function getStepList(int $student_id)
    {
        $selects = [
            DB::raw('steps.name as text'),
            'steps.id as value',
            'curriculums.name as description'
        ];
        $ret = Step::query()
            ->select($selects)
            ->join('curriculums', 'curriculums.id', 'steps.curriculum_id')
            ->leftJoin('course_curriculums', 'course_curriculums.curriculum_id', 'curriculums.id')
            ->leftJoin('courses', 'courses.id', 'course_curriculums.course_id')
            ->leftJoin('students', 'courses.id', 'students.course_id')
            ->where('students.id', $student_id);
        return $ret->orderBy('course_curriculums.order')
            ->orderBy('steps.order')
            ->get();
    }

    /**
     * @param int $curriculum_id
     * @return array
     */
    public function getListByCurriculumId(int $curriculum_id): array
    {
        return Step::query()
            ->where('curriculum_id', $curriculum_id)
            ->orderBy('order')
            ->get()->toArray();
    }

    public function getModelAndSetParams(array $data, ?int $curriculum_id, int $order): Step
    {
        if (isset($data['id'])) {
            $step = $this->find($data['id']);
        } else {
            $step = new Step();
        }
        $step->curriculum_id = $curriculum_id;
        $step->name = $data['name'];
        $step->content = $data['content'];
        $step->image = $data['image'] ?? null;
        $step->target_days = $data['target_days'];
        $step->deadline_days = $data['deadline_days'];
        $step->order = $order;
        return $step;
    }


    /**
     * @param $curriculum_id
     * @param $student_id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getStepsAndLatestProgressByCurriculumIdAndStudentId($curriculum_id, $student_id)
    {
        return Step::query()->
        select([
            'steps.*',
            DB::raw('count(progresses.id) as date_count'),
            'progress_status' => function (\Illuminate\Database\Query\Builder $builder) use ($student_id) {
                $builder->select('progresses.progress_status')
                    ->from('progresses')
                    ->whereRaw('steps.id = progresses.step_id')
                    ->where('progresses.student_id', $student_id)
                    ->whereNull('progresses.deleted_at')
                    ->orderBy('progresses.date', 'desc')
                    ->limit(1);
            }
        ])->where('steps.curriculum_id', $curriculum_id)
            ->leftJoin('progresses', function (JoinClause $join) use ($student_id) {
                $join->on('progresses.step_id', 'steps.id')
                    ->where('progresses.student_id', $student_id)
                    ->whereNull('progresses.deleted_at');
            })
            ->groupBy('steps.id')
            ->orderBy('steps.order')
            ->get();
    }

    public function getStepsDateCountByCurriculumAndStudent(int $curriculum_id, int $student_id)
    {
        return Step::query()
            ->select([
                'steps.*',
                DB::raw('count(progresses.id) as date_count'),
                // DB::raw('max(progresses.percent) as latest_percent'),
                DB::raw('max(progresses.progress_status) as latest_progress_status'),
                DB::raw('max(progresses.date) as latest_date')
            ])
            ->leftJoin('progresses', function (JoinClause $join) use ($student_id) {
                $join->on('progresses.step_id', 'steps.id')
                    ->where('progresses.student_id', $student_id)
                    ->whereNull('progresses.deleted_at');
            })
            ->where('steps.curriculum_id', $curriculum_id)
            ->groupBy('steps.id')
            ->orderBy('steps.order')->get();
    }

    public function getCurrentCurriculumId(int $student_id)
    {
        $sql = <<<SQL
select `steps`.`curriculum_id`
from `steps`
         inner join `students` on `students`.`id` = ?
         inner join `course_curriculums` on `steps`.`curriculum_id` = `course_curriculums`.`curriculum_id` and
                                            `course_curriculums`.`course_id` = students.course_id
         left join `progresses` on `progresses`.`step_id` = `steps`.`id` and `progresses`.`student_id` = students.id
-- where (`progresses`.`percent` is null or `progresses`.`percent` <> 100)
where (`progresses`.`progress_status` is null or `progresses`.`progress_status` <> 3)
  and `steps`.`deleted_at` is null
group by `steps`.`id`
order by max(progresses.date), max(course_curriculums.order), steps.order
limit 1
SQL;
        $ret = DB::selectOne($sql, [$student_id]);
        return $ret->curriculum_id ?? null;
    }
}

