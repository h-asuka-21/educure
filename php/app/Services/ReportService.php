<?php


namespace App\Services;


use App\Models\Report\Report;
use App\Models\Report\Repository\ReportRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class ReportService extends AbstractService
{

    private ReportRepositoryInterface $report;

    public function __construct(
        ReportRepositoryInterface $report
    )
    {
        $this->report = $report;
    }

    /**
     * @param int|null $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        $report_list = $this->report->getList($company_id);
        return $report_list;
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $ret = $this->report->find($id);
        return $ret !== null ? $ret->toArray() : null;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getUserByReportId(int $id)
    {
        return $this->teacher_report->getUserByReportId($id);
    }

    public function getAttendanceCount($schedule_id): int
    {
        return $this->report->getAttendanceCount($schedule_id);
    }

}
