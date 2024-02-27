<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CompanyService;
use App\Services\ScheduleService;
use App\Services\ReservationService;
use App\Services\StudentService;
use Illuminate\Support\Facades\Log;

class CreateScheduleAndReservationBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:createscheduleandreservation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'スケジュール・予約データ追加バッチ';

    private CompanyService $company_service;
    private ScheduleService $schedule_service;
    private ReservationService $reservation_service;
    private StudentService $student_service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct
    (
        CompanyService $company_service,
        ScheduleService $schedule_service,
        ReservationService $reservation_service,
        StudentService $student_service
    )
    {
        $this->company_service = $company_service;
        $this->schedule_service = $schedule_service;
        $this->reservation_service = $reservation_service;
        $this->student_service = $student_service;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info($this->description . "開始");

        // 企業一覧取得
        $company_list = $this->company_service->getCompanyList();

        foreach ($company_list as $company) {
            // スケジュール登録
            $schedule = $this->schedule_service->createScheduleForBatch($company['id']);
            // 企業ごとに所属する受講生の予約を登録
            $students = $this->student_service->getList($company['id']);
            foreach ($students['data'] as $student) {
                $this->reservation_service->reserveForBatch($student['id'], $schedule['id']);
            }
        }
        Log::info($this->description . "終了");
        return 0;
    }
}
