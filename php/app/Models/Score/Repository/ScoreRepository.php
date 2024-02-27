<?php


namespace App\Models\Score\Repository;

use App\Models\AbstractRepository;
use App\Models\Score\Score;
use App\Models\Test\Test;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScoreRepository extends AbstractRepository implements ScoreRepositoryInterface
{

    protected array $searchable = [
        'students__name' => 'like',
        'companies__id' => '=',
        'tests.name' => 'like',
        'tests.test_type' => '=',
        'scores.updated_at' => 'date',
    ];
    protected string $tbl_name = 'scores';
    protected array $sortable = [
        'students__name' => 'students.name',
        'name' => 'tests.name',
        'score' => 'scores.score',
        'company_name' => 'companies.id',
        'test_type' => 'tests.test_type',
        'question_count' => 'question_count',
        'updated_at' => 'scores.updated_at',

    ];

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return Score::query()->find($id);
    }

    public function save(Score $score)
    {
        try {
            return Score::query()->updateOrCreate(
                ['id' => $score->id],
                [
                    'student_id' => $score->student_id,
                    'test_id' => $score->test_id,
                    'score' => $score->score,
                    'choices' => $score->choices,
                ]
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    public function getModelAndSetParams(array $data): Score
    {
        $score = null;
        if (!empty($data['score_id'])) {
            $score = $this->find($data['score_id']);
        }
        if ($score === null) {
            $score = new Score();
            $score->student_id = $data['student_id'];
            $score->test_id = $data['test_id'];
        }
        $score->score = $data['score'];
        $score->choices = $data['choices'];
        return $score;
    }

    /**
     * @param int $test_id
     * @return array
     */
    public function getStudentsByTestId(int $test_id): array
    {
        $query = request()->query();
        return Score::query()
            ->select([
                'scores.*',
                'students.name',
            ])
            ->where('test_id', $test_id)
            ->where(function (Builder $builder) use ($query) {
                $this->search($builder, $query);
            })
            ->leftJoin('students', 'scores.student_id', 'students.id')
            ->paginate($this->getPerPage($query))
            ->toArray();
    }

    public function getAverageScores(int $student_id)
    {
        return Score::query()
            ->select([
                'think_count' => function (\Illuminate\Database\Query\Builder $builder) use ($student_id) {
                    $this->getAvgByTypeAndStudent($builder, $student_id, true);
                },
                DB::raw("sum(if(tests.test_type = 2,scores.score,0)) as think_total"),
                'comprehension_count' => function (\Illuminate\Database\Query\Builder $builder) use ($student_id) {
                    $this->getAvgByTypeAndStudent($builder, $student_id);
                },
                DB::raw("sum(if(tests.test_type <> 2,scores.score,0)) as comprehension_total"),
            ])
            ->join('tests', 'tests.id', 'scores.test_id')
            ->where('scores.student_id', $student_id)
            ->get();
    }

    public function getComprehensionScores(int $student_id)
    {
        return Score::query()
            ->select([
                'scores.score',
                DB::raw('count(questions.id) as question_count')
            ])
            ->join('tests', 'tests.id', 'scores.test_id')
            ->join('questions', 'questions.test_id', 'tests.id')
            ->where('scores.student_id', $student_id)
            ->where('test_type', '<>', Test::TYPE['cab'])
            ->groupBy('scores.test_id', 'scores.score')
            ->get();
    }

    private function getAvgByTypeAndStudent(\Illuminate\Database\Query\Builder $builder, int $student_id, $think = false)
    {
        return $builder->select([
            DB::raw('count(questions.id)'),
        ])
            ->from('scores')
            ->join('tests', function (JoinClause $join) use ($think) {
                $join->on('tests.id', 'scores.test_id');
                if ($think) {
                    $join->where('test_type', Test::TYPE['cab']);
                } else {
                    $join->where('test_type', '<>', Test::TYPE['cab']);
                }
            })
            ->join('questions', 'questions.test_id', 'tests.id')
            ->where('scores.student_id', $student_id);

    }

    /**
     * @param $student_id
     * @param $test_id
     * @return Score|null
     */
    public function getRecordByStudentIdAndTestId($student_id, $test_id): ?Score
    {
        return Score::query()
            ->select([
                'scores.*',
            ])
            ->where('student_id', $student_id)
            ->where('test_id', $test_id)
            ->first();
    }
}
