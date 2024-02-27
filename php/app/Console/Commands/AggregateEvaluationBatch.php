<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StudentService;
use App\Services\ProgressService;
use App\Services\InterviewHistoryService;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Log;

class AggregateEvaluationBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:aggregateevaluationbatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生徒評価集計バッチ';

    private StudentService $student_service;
    private ProgressService $progress_service;
    private InterviewHistoryService $interview_service;
    private CompanyService $company_service;

    public $progress_evaluation_flg = false;
    public $sales_evaluation_flg = false;

    /**
     * Create a new command instance.
     * @param StudentService $student_service
     * @param ProgressService $progress_service
     * @param InterviewHistoryService $interview_service
     * @param CompanyService $company_service
     *
     * @return void
     */
    public function __construct
    (
        StudentService $student_service,
        ProgressService $progress_service,
        InterviewHistoryService $interview_service,
        CompanyService $company_service
    )
    {
        $this->student_service = $student_service;
        $this->progress_service = $progress_service;
        $this->interview_service = $interview_service;
        $this->company_service = $company_service;
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

        // 企業単位でループ
        foreach ($company_list as $company) {
            // 企業が削除されている場合スキップ
            if (isset($company['deleted_at'])) {
                continue;
            }
            // 生徒一覧をページネーションなしで取得
            $students = $this->student_service->getList($company['id'], false);

            // 生徒ごとにループを開始
            foreach ($students as $student) {
                $student = $student->toArray();
                // 受講中以外スキップ
                if ($student['after_graduation_flg'] != 0) {
                    continue;
                }
                // 最新のステップが100%の進捗情報を取得
                $progress = $this->progress_service->getClearedStep($student['course_id'], $student['id']);
                // カリキュラムの最新進捗の日時がNULLでない場合 true
                if (isset($progress['date'])) {
                    $this->progress_evaluation_flg = true;
                } else {
                    $this->progress_evaluation_flg = false;
                }

                // 営業評価スキップのbool
                if (!isset($student['aggregate_date'])) {
                    // 集計日がない場合はtrue
                    $this->sales_evaluation_flg = true;
                } else {
                    // 集計日がある場合
                    // 営業評価が集計日より未来に存在するかチェック
                    $this->sales_evaluation_flg = $this->interview_service->getEvaluationCountByStudentIdAndDate($student['id'], $student['aggregate_date']);
                }
                // データに更新がない場合処理スキップ
                if ($this->progress_evaluation_flg == false && $this->sales_evaluation_flg == false) {
                    continue;
                }

                // 各フラグに応じた各項目の評価を取得
                $params = [
                    'student_id' => $student['id']
                ];
                if ($this->progress_evaluation_flg) {
                    $params['date'] = $progress['date'];
                }

                $result = $this->student_service->getEvaluationBatch($params, $this->progress_evaluation_flg, $this->sales_evaluation_flg, $student);

                $this->student_service->updateforScores($result);
            }
        }
        Log::info($this->description . "終了");
    }
}
