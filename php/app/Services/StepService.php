<?php


namespace App\Services;


use App\Models\Progress\Progress;
use App\Models\Progress\Repository\ProgressRepositoryInterface;
use App\Models\Step\Repository\StepRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Step\Step;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class StepService extends AbstractService
{
    private StepRepositoryInterface $steps;
    private ProgressRepositoryInterface $progress;

    public function __construct(
        StepRepositoryInterface $steps,
        ProgressRepositoryInterface $progress
    )
    {
        $this->steps = $steps;
        $this->progress = $progress;
    }

    /**
     * @param int|null $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        return $this->steps->getList($company_id);
    }

    /**
     * 詳細表示
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getById(int $id): ?array
    {
        $ret = $this->steps->find($id);
        return $ret !== null ? $ret->toArray() : null;
    }

    public function getAutocomplete(int $company_id): array
    {
        $list = $this->steps->getAutocomplete($company_id);
        $result = $list->toArray();
        if (request()->student_id) {
            foreach ($list as $key => $item) {
                $data = $this->progress->getLatestStatusByStepIdAndStudentId($item->value, request()->student_id);
                if ($data === null) {
                    continue;
                }
                $result[$key]['progress_id'] = $data->id;
                $result[$key]['progress_status'] = $data->progress_status;
                $result[$key]['description'] = "前回の進捗：【" . config('const.PROGRESS_STATUS')["$data->progress_status"] . "】";
            }
        }

        return $result;
    }
}
