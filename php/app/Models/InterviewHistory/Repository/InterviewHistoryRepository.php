<?php


namespace App\Models\InterviewHistory\Repository;

use App\Models\AbstractRepository;
use App\Models\InterviewHistory\InterviewHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class InterviewHistoryRepository extends AbstractRepository implements InterviewHistoryRepositoryInterface
{
    protected array $searchable = [
        'interview_histories.student_id' => '=',
        'interview_histories.sales_evaluation' => 'like',
    ];

    /**
     * @param int $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        $query = request()->query();
        return InterviewHistory::query()
            ->select([
                DB::raw('count(interview_histories.student_id) as interview_count'),
                'interview_histories.student_id',
                'students.name',
            ])
            ->where(function (Builder $builder) use ($query) {
                $this->search($builder, $query);
            })
            ->leftJoin('students', 'students.id', 'interview_histories.student_id')
            ->where('company_id', $company_id)
            ->groupBy('interview_histories.student_id', 'students.name')
            ->paginate($this->getPerPage($query))
            ->toArray();
    }


    public function getListByCompanyId(?int $company_id): array
    {
        $query = request()->query();
        $result = InterviewHistory::query()
            ->select([
                'interview_histories.*',
                'interview_histories.sales_evaluation as evaluation',
                'interview_histories.evaluation_reason as reason',
                'interview_histories.updated_at as date',
                'students.name as student_name',
                'users.name as user_name'
            ])
            ->join('users', 'users.id', 'interview_histories.user_id')
            ->join('students', 'students.id', 'interview_histories.student_id')
            ->orderBy('interview_histories.updated_at', 'desc');

        if (!is_null($company_id)) {
            $result->where('students.company_id', $company_id);
        }

        if (isset($query['date'])) {
            $result->whereDate('interview_histories.updated_at', $query['date']);
        }

        if (isset($query['student_name'])) {
            $result->where('students.name', 'like', "%{$query['student_name']}%");
        }

        if (isset($query['evaluation'])) {
            $result->where('interview_histories.sales_evaluation', $query['evaluation']);
        }

        if (isset($query['user_name'])) {
            $result->where('users.name', 'like', "%{$query['user_name']}%");
        }

        return $result
            ->paginate($this->getPerPage($query))
            ->toArray();
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return InterviewHistory::query()->find($id);
    }


    /**
     * @param InterviewHistory $interview_history
     * @return bool
     */
    public function save(InterviewHistory $interview_history)
    {
        return DB::transaction(function () use ($interview_history) {
            InterviewHistory::updateOrCreate(
                ['id' => $interview_history->id],
                [
                    'student_id' => $interview_history->student_id,
                    'sales_evaluation' => $interview_history->sales_evaluation,
                    'evaluation_reason' => $interview_history->evaluation_reason,
                    'user_id' => $interview_history->user_id,
                ]
            );
            return true;
        });
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return InterviewHistory::find($id)->delete();
    }

    /**
     * @param int $student_id
     * @return int
     */
    public function getEvaluationAverageByStudentId(int $student_id): ?int
    {
        $result = InterviewHistory::query()
            ->where('student_id', $student_id);
        if ($result->count() === 0) {
            return null;
        }
        return $result->avg('sales_evaluation');
    }


    public function getEvaluationCountByStudentIdAndDate(int $student_id, string $date): bool
    {
        $result = InterviewHistory::query()
            ->where('student_id', $student_id)
            ->whereDate('created_at', '>=', $date);

        if ($result->count() === 0) {
            return false;
        }
        return true;
    }

    /**
     * @param int $student_id
     * @return array|Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByStudentId(int $student_id)
    {
        return InterviewHistory::query()
            ->select([
                'interview_histories.*',
                'interview_histories.sales_evaluation as evaluation',
                'interview_histories.evaluation_reason as reason',
                'interview_histories.updated_at as date',
                'users.name as user_name'
            ])
            ->join('users', 'users.id', 'interview_histories.user_id')
            ->where('student_id', $student_id)
            ->get();
    }

}
