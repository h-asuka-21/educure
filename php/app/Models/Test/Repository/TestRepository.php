<?php


namespace App\Models\Test\Repository;

use App\Models\AbstractRepository;
use App\Models\Test\Test;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestRepository extends AbstractRepository implements TestRepositoryInterface
{

    protected array $searchable = [
        'tests.name' => 'like',
        'tests.test_type' => '='
    ];

    protected string $tbl_name = 'tests';

    protected array $sortable = [
        'name' => 'tests.name',
        'test_type' => 'tests.test_type',
        'question_count' => 'question_count',
        'avg_score' => 'avg_score',
    ];

    // /**
    //  * @param int $company_id
    //  * @return array
    //  */
    public function getList(?int $company_id): array
    {
        $query = Test::query();

        // $company_idが指定された場合は、会社IDに基づいてデータをフィルタリング
        if ($company_id !== null) {
            $query->where('company_id', $company_id);
        }
        $result = $query->get()->toArray();
        return $result;
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return Test::query()->find($id);
    }

    public function save(Test $test)
    {
        try {
            return Test::query()->updateOrCreate(
                ['id' => $test->id],
                [
                    'name' => $test->name,
                    'test_type' => $test->test_type,
                    'test_time' => $test->test_time,
                ]
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    public function getModelAndSetParams(array $data, ?int $id): Test
    {
        if ($id) {
            $test = $this->find($id);
        } else {
            $test = new Test();
        }
        $test->name = $data['name'];
        $test->test_type = $data['test_type'];
        $test->test_time = $data['test_time'];
        return $test;
    }

    /**
     * 未回答のCABテストを取得
     * @param int $student_id
     * @param int $type
     * @param int|null $curriculum_id
     * @param int|null $course_id
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getUnansweredTest(int $student_id, int $type = Test::TYPE['curriculum'], ?int $curriculum_id = null, int $course_id = null)
    {
        //　一つ前のカリキュラムのテストは複数回受講可能
        if ($type === Test::TYPE['curriculum']) {
            return Test::query()
                ->select(['tests.*'])
                ->leftJoin('curriculums', 'curriculums.test_id', 'tests.id')
                ->where(
                    function (Builder $builder) use ($type, $curriculum_id, $course_id) {
                        $builder->where('tests.test_type', $type);
                        if ($curriculum_id !== null) {
                            $builder->where('curriculums.id', $curriculum_id);
                        }
                    }
                )
                ->first();
        }

        $ret = Test::query()
            ->select(['tests.*'])
            ->leftJoin('scores',
                function (JoinClause $join) use ($student_id) {
                    $join->on('scores.test_id', 'tests.id')
                        ->where('scores.student_id', $student_id);
                }
            )
            ->whereNull('scores.id');
        if ($type === Test::TYPE['cab']) {
            $ret->leftJoin('courses', 'courses.first_test_id', 'tests.id');
        } elseif ($type === Test::TYPE['comp']) {
            $ret->leftJoin('courses', 'courses.general_test_id', 'tests.id');
        }
        return $ret
            ->where(
                function (Builder $builder) use ($type, $curriculum_id, $course_id) {
                    $builder->where('tests.test_type', $type);
                    if ($curriculum_id !== null) {
                        $builder->where('curriculums.id', $curriculum_id);
                    }
                    if ($course_id !== null) {
                        $builder->where('courses.id', $course_id);
                    }
                }
            )
            ->first();
    }

    public function getAutocomplete(?int $type): array
    {
        $result = Test::query()
            ->select(['name as text', 'id as value']);
        if ($type !== null) {
            $result->where('test_type', $type);
        }
        return $result->get()->toArray();
    }

}
