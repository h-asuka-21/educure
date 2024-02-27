<?php


namespace App\Services;

use App\Models\MissingEvaluationItem\MissingEvaluationItem;
use App\Models\MissingEvaluationItem\Repository\MissingEvaluationItemRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MissingEvaluationItemService extends AbstractService
{
    private MissingEvaluationItemRepositoryInterface $missing_evaluation_item;

    public function __construct(
        MissingEvaluationItemRepositoryInterface $missing_evaluation_item
    )
    {
        $this->missing_evaluation_item = $missing_evaluation_item;
    }

    public function getList(?int $company_id): ?array
    {
        // TODO: Implement getList() method.
    }

    public function getById(int $id): ?array
    {
        // TODO: Implement getById() method.
    }

    public function getListByCompanyId(?int $company_id): array
    {
        return $this->missing_evaluation_item->getListByCompanyId($company_id);
    }

    /**
     * slackチャンネルIDを格納
     * @param $request
     *
     * @return bool
     */
    public function createMissingEvaluationItem($request)
    {
        try {
            DB::beginTransaction();
            $missing_evaluation_item = new MissingEvaluationItem();
            $missing_evaluation_item->student_id = $request['student_id'];
            $missing_evaluation_item->missing_type = $request['missing_type'];
            $missing_evaluation_item->reason = $request['reason'];
            /** @var  $missing_evaluation_item MissingEvaluationItem */
            $this->missing_evaluation_item->save($missing_evaluation_item);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    /**
     * @return bool
     */
    public function deleteMissingEvaluationItem()
    {
        return $this->missing_evaluation_item->delete();
    }
}
