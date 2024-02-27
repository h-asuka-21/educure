<?php


namespace App\Models\Course\Repository;

use App\Models\AbstractRepository;
use App\Models\Course\Course;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class CourseRepository extends AbstractRepository implements CourseRepositoryInterface
{
    protected array $searchable = [
        'courses.name' => 'like',
        'courses.created_at' => '=',
        'courses.updated_at' => '=',
    ];
    protected string $tbl_name = 'courses';

    protected array $sortable = [
        'name' => 'courses.name',
        'created_at' => 'courses.created_at',
        'updated_at' => 'courses.updated_at'
    ];

    /**
     * @param int $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        $query = request()->query();
        $result = Course::query();
        if (!empty($company_id)) {
            $result->where('company_id', $company_id);
        }
        return $result
            ->where(function (Builder $builder) use ($query) {
                $this->search($builder, $query);
            })
            ->orderByRaw($this->setOrder())
            ->paginate($this->getPerPage($query))
            ->toArray();
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return Course::query()->find($id);
    }


    /**
     * @param Course $course
     * @return false|Builder|\Illuminate\Database\Eloquent\Model
     */
    public function save(Course $course)
    {
        try {
            return Course::query()->updateOrCreate(
                ['id' => $course->id],
                [
                    'name' => $course->name,
                    'general_test_id' => $course->general_test_id,
                    'first_test_id' => $course->first_test_id
                ]
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    public function getAutocompleteItem(): array
    {
        return Course::query()
            ->select([
                'id as value',
                'name as text'
            ])
            ->get()->toArray();
    }

    public function getAutocompleteWithCompanyIdItem(?int $company_id): array
    {
        $ret = Course::query()
            ->select([
                'courses.id as value',
                'courses.name as text'
            ]);
        if ($company_id) {
            $ret->join('course_groups', 'course_groups.course_id', 'courses.id')
                ->where('course_groups.company_id', $company_id);
        }
        return $ret->get()->toArray();
    }
}
