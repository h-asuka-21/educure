<?php


namespace App\Models\Curriculum\Repository;

use App\Models\AbstractRepository;
use App\Models\Curriculum\Curriculum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\DistinctPaginator;


class CurriculumRepository extends AbstractRepository implements CurriculumRepositoryInterface
{
    use DistinctPaginator;

    protected array $searchable = [
        'curriculums.name' => 'like',
    ];

    protected string $tbl_name = 'curriculums';

    protected array $sortable = [
        'name' => 'curriculums.name',
        'created_at' => 'curriculums.created_at',
        'updated_at' => 'curriculums.updated_at',
    ];

    /**
     * @param int $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        $query = request()->query();
        $ret = Curriculum::query()
            ->select([
                'curriculums.*',
            ])
            ->where(function (Builder $builder) use ($query) {
                $this->search($builder, $query);
            })
            ->orderByRaw($this->setOrder());

        if ($company_id) {
            $ret->join('course_curriculums', 'course_curriculums.curriculum_id', 'curriculums.id')
                ->join('courses', 'courses.id', 'course_curriculums.course_id')
                ->join('course_groups', 'course_groups.course_id', 'courses.id')
                ->where('course_groups.company_id', $company_id)
                ->distinct();
        }

        return $this->distinctPaginate($ret, $this->getPerPage($query), ['curriculums.id'])->toArray();
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return Curriculum::query()->find($id);
    }

    public function save(Curriculum $curriculum)
    {
        try {
            return Curriculum::query()->updateOrCreate(
                ['id' => $curriculum->id],
                [
                    'name' => $curriculum->name,
                    'zip' => $curriculum->zip,
                    'zip_name' => $curriculum->zip_name,
                    'test_id' => $curriculum->test_id
                ]
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function getModelAndSetParams(array $data, ?int $id): Curriculum
    {
        if ($id) {
            $curriculum = $this->find($id);
        } else {
            $curriculum = new Curriculum();
        }
        $curriculum->name = $data['name'];
        $curriculum->test_id = $data['test_id'] ?? null;
        $curriculum->zip = $data['zip'] ?? null;
        $curriculum->zip_name = $data['zip_name'] ?? null;

        return $curriculum;
    }

    public function getAutocompleteItem(): array
    {
        return Curriculum::query()
            ->select([
                'id as value',
                'name as text'
            ])
            ->get()->toArray();
    }

    public function getAutocompleteItemWithCompanyId(?int $company_id): array
    {
        $ret = Curriculum::query()
            ->select([
                'curriculums.id as value',
                'curriculums.name as text'
            ]);
        if ($company_id) {
            $ret->join('course_curriculums', 'course_curriculums.curriculum_id', 'curriculums.id')
                ->join('courses', 'courses.id', 'course_curriculums.course_id')
                ->join('course_groups', 'course_groups.course_id', 'courses.id')
                ->where('course_groups.company_id', $company_id)
                ->distinct();
        }
        return $ret->get()->toArray();
    }

    /**
     * @param int $course_id
     * @param bool $paginate
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByCourseId(int $course_id, bool $paginate = false)
    {
        $result = Curriculum::query()
            ->select([
                'curriculums.*',
                DB::raw('`course_curriculums`.`order` + 1 as `order`')
            ])
            ->leftJoin('course_curriculums', 'course_curriculums.curriculum_id', 'curriculums.id')
            ->where('course_curriculums.course_id', $course_id)
            ->orderBy('course_curriculums.order', 'asc');
        if ($paginate) {
            return $result->paginate();
        }
        return $result->get();
    }


    public function getCurriculumTarget(int $curriculum_id): int
    {
        return Curriculum::query()
            ->select([
                DB::raw('ifnull(sum(steps.target_days),0) as target_days')
            ])
            ->join('steps', 'steps.curriculum_id', 'curriculums.id')
            ->where('curriculums.id', $curriculum_id)
            ->get()->toArray()[0]['target_days'];
    }

    public function getCurriculumDeadline(int $curriculum_id): int
    {
        return Curriculum::query()
            ->select([
                DB::raw('ifnull(sum(steps.deadline_days),0) as deadline_days')
            ])
            ->join('steps', 'steps.curriculum_id', 'curriculums.id')
            ->where('curriculums.id', $curriculum_id)
            ->get()->toArray()[0]['deadline_days'];
    }
}
