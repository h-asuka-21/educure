<?php


namespace App\Models\MissingEvaluationItem\Repository;

use App\Models\AbstractRepository;
use App\Models\MissingEvaluationItem\MissingEvaluationItem;
use App\Models\Company\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class MissingEvaluationItemRepository extends AbstractRepository implements MissingEvaluationItemRepositoryInterface
{
    public function getListByCompanyId(?int $company_id): array
    {
        $query = request()->query();
        $result = MissingEvaluationItem::query()
            ->select([
                'missing_evaluation_items.*',
                'students.name as student_name',
                'companies.name as company_name'
            ])
            ->join('students', 'students.id', 'missing_evaluation_items.student_id')
            ->join('companies', 'companies.id', 'students.company_id')
            ->orderBy('missing_evaluation_items.updated_at', 'desc');

        if (!is_null($company_id)) {
            $result->where('students.company_id', $company_id);
        }


        if (isset($query['student_name'])) {
            $result->where('students.name', 'like', "%{$query['student_name']}%");
        }

        if (isset($query['companies__id'])) {
            $result->where('students.company_id', $query['companies__id']);
        }

        if (isset($query['missing_type'])) {
            $result->where('missing_evaluation_items.missing_type', $query['missing_type']);
        }

        return $result
            ->paginate($this->getPerPage($query))
            ->toArray();
    }

    /**
     * @param MissingEvaluationItem $MissingEvaluationItem
     * @return MissingEvaluationItem
     */
    public function save(MissingEvaluationItem $missingEvaluationItem)
    {
        return DB::transaction(function () use ($missingEvaluationItem) {
            return MissingEvaluationItem::updateOrCreate(
                ['id' => $missingEvaluationItem->id],
                [
                    'student_id' => $missingEvaluationItem->student_id,
                    'missing_type' => $missingEvaluationItem->missing_type,
                    'reason' => $missingEvaluationItem->reason,
                ]
            );
        });
    }

    public function delete()
    {
        return DB::table('missing_evaluation_items')->truncate();
    }
}
