<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StudentService;
use App\Services\ProgressService;
use App\Services\InterviewHistoryService;
use App\Services\TestService;
use App\Services\CompanyService;
use App\Services\ReservationService;
use App\Services\MissingEvaluationItemService;
use App\Models\MissingEvaluationItem\MissingEvaluationItem;
use Illuminate\Support\Facades\Log;

class CheckMissingEvaluationItemBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:checkmissingevaluationitem';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '評価不足項目確認バッチ';

    private StudentService $student_service;
    private ProgressService $progress_service;
    private InterviewHistoryService $interview_service;
    private TestService $test_service;
    private CompanyService $company_service;
    private ReservationService $reservation_service;
    private MissingEvaluationItemService $missing_evaluation_item_service;

    /**
     * Create a new command instance.
     * @param StudentService $student_service
     * @param ProgressService $progress_service
     * @param InterviewHistoryService $interview_service
     * @param TestService $test_service
     * @param CompanyService $company_service
     * @param ReservationService $reservation_service
     * @param MissingEvaluationItemService $missing_evaluation_item_service
     *
     * @return void
     */
    public function __construct
    (
        StudentService $student_service,
        ProgressService $progress_service,
        InterviewHistoryService $interview_service,
        TestService $test_service,
        CompanyService $company_service,
        ReservationService $reservation_service,
        MissingEvaluationItemService $missing_evaluation_item_service
    )
    {
        $this->student_service = $student_service;
        $this->progress_service = $progress_service;
        $this->interview_service = $interview_service;
        $this->test_service = $test_service;
        $this->company_service = $company_service;
        $this->reservation_service = $reservation_service;
        $this->missing_evaluation_item_service = $missing_evaluation_item_service;
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
        // 処理開始時に不足項目テーブルを削除
        $this->missing_evaluation_item_service->deleteMissingEvaluationItem();

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

                // 挿入データ初期化
                $insert_datas = array();

                // 受講中以外スキップ
                if ($student['after_graduation_flg'] != 0) {
                    continue;
                }

                // カリキュラムの進捗が100%のものを取得
                $cleared_curriculums = $this->progress_service->getEndCurriculums($student['course_id'], $student['id']);

                // 終了しているカリキュラムがない = 第1回目が終了していない
                if (empty($cleared_curriculums)) {
                    $insert_datas[] = array(
                        'student_id' => $student['id'],
                        'missing_type' => MissingEvaluationItem::TYPE['progress_score'],
                        'reason' => "一番最初のカリキュラムが終了していません。"
                    );
                } else {
                    // 最大進捗のカリキュラムのステップ完了日を取得
                    $progress_detai = $this->progress_service->getClearedStep($student['course_id'], $student['id']);

                    // 講師評価 終了しているカリキュラムまでの入力漏れチェック
                    if (!empty($this->reservation_service->checkUnTeacherScore($student['id'], $progress_detai['date']))) {
                        $insert_datas[] = $this->reservation_service->checkUnTeacherScore($student['id'], $progress_detai['date']);
                    }

                    // 出席率 終了しているカリキュラムまでの出席漏れチェック
                    if (!empty($this->reservation_service->checkUnAttendance($student['id'], $progress_detai['date']))) {
                        $insert_datas[] = $this->reservation_service->checkUnAttendance($student['id'], $progress_detai['date']);
                    }

                    // 報告率 終了しているカリキュラムまでの出席と講師評価の入力済み（Slackでの報告）
                    if (!empty($this->reservation_service->checkUnReport($student['id'], $progress_detai['date']))) {
                        $insert_datas[] = $this->reservation_service->checkUnReport($student['id'], $progress_detai['date']);
                    }

                    // 理解度(カリキュラムテストを受けれる状態で受けていない)
                    if (!empty($this->test_service->checkUnAnsweredCurriculumTest($student['id'], $cleared_curriculums))) {
                        $results = $this->test_service->checkUnAnsweredCurriculumTest($student['id'], $cleared_curriculums);

                        foreach ($results as $result) {
                            $insert_datas[] = $result;
                        }
                    }

                }
                // 営業評価 面談履歴有無
                if (!empty($this->interview_service->checkUnInterviewHistory($student['id']))) {
                    $insert_datas[] = $this->interview_service->checkUnInterviewHistory($student['id']);
                }

                // 思考力 CABテスト未受験
                if (!empty($this->test_service->checkUnAnsweredCabTest($student['id']))) {
                    $insert_datas[] = $this->test_service->checkUnAnsweredCabTest($student['id']);
                }


                if (!empty($insert_datas)) {
                    foreach ($insert_datas as $insert_data) {
                        $this->missing_evaluation_item_service->createMissingEvaluationItem($insert_data);
                    }
                }
            }


        }
        Log::info($this->description . "終了");
    }
}
