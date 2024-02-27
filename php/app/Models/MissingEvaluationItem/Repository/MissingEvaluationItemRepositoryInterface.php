<?php


namespace App\Models\MissingEvaluationItem\Repository;

use App\Models\MissingEvaluationItem\MissingEvaluationItem;
use Illuminate\Database\Eloquent\Builder;

interface MissingEvaluationItemRepositoryInterface
{
    public function getListByCompanyId(?int $company_id): array;

    /**
     * @param MissingEvaluationItem $missingevaluationitem
     * @return MissingEvaluationItem
     */
    public function save(MissingEvaluationItem $missingEvaluationItem);

    public function delete();
}
