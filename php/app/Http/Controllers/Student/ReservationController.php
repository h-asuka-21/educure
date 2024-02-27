<?php


namespace App\Http\Controllers\Student;


use App\Http\Controllers\AbstractStudentController;
use App\Services\CompanyService;
use App\Services\ScheduleService;
use App\Services\ReportService;
use App\Services\ReservationService;
use App\Services\ProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Mockery\Exception;

class ReservationController extends AbstractStudentController
{
    private const CONTROLLER_NAME = '出席予約';
    private CompanyService $company_service;
    private ScheduleService $schedule_service;
    private ReservationService $reserve;
    private ReportService $report;
    private ProgressService $progress;

    public function __construct(
        CompanyService $company_service,
        ScheduleService $schedule_service,
        ReservationService $reserve,
        ReportService $report,
        ProgressService $progress
    )
    {
        $this->company_service = $company_service;
        $this->schedule_service = $schedule_service;
        $this->reserve = $reserve;
        $this->report = $report;
        $this->progress = $progress;
        parent::__construct();
    }

    public function reserve(Request $request)
    {
        $action_str = self::CONTROLLER_NAME . 'の登録';
        try {
            $student_id = $this->getId();
            $data = request()->toArray();
            $today = date('Y-m-d');
            // スケジュール情報取得
            $schedule = $this->schedule_service->getById($data['schedule_id']);
            if ($this->reserve->reserve($student_id)) {
                // 当日予約時
                if ($today == $schedule['date'] && request()->delete == false) {
                    // slackチャンネル招待
                    $this->slackChannelInvitation();
                }
                return $this->successResponse($action_str);
            }
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->errorResponse($action_str);
        }
    }

    public function today(): JsonResponse
    {
        try {
            $student_id = $this->getId();
            // return response()->json($this->reserve->getTodayReserveAndReport($student_id));
            $result = $this->reserve->getTodayReserveAndReport($student_id);
            $result['progress'] = $this->progress->getPrgoressedStep($student_id);

            return response()->json($result);;
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得');
        }
    }

    /**
     * slackチャンネル招待処理
     *
     * @return void
     */
    public function slackChannelInvitation()
    {
        // 企業IDを取得
        $company_id = $this->getCompanyId();
        // 企業情報を取得
        $company = $this->company_service->getById($company_id);
        // 受講生情報を取得
        $email = $this->getEmail();
        // slackチャンネル情報を取得(既にバッチで作成した情報)
        $slack_channel = $this->company_service->getSlackChannel($company_id);

        // slackトークン設定済みのみ
        if (!empty($company['slack_token'])) {
            // slack_idを取得
            $slack_id = $this->lookupByEmail($company['slack_token'], $email);
            // slackチャンネルIDかつslack_idが必須
            if (isset($slack_channel['channel_id']) && !empty($slack_id)) {
                // slackチャンネルに受講生を招待
                $this->invitationUser($email, $company['slack_token'], $slack_channel['channel_id'], $slack_id);
            } else {
                Log::error("SlackチャンネルIDまたはslack_idが存在しないためチャンネル招待に失敗しました。");
            }
        }
    }

    /**
     * slackチャンネル招待
     */
    public function invitationUser($email, $token, $channel_id, $slack_id)
    {
        $base_url = "https://slack.com/api/conversations.invite?token={$token}&channel={$channel_id}&users={$slack_id}";

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $base_url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $result = json_decode($response, true);

        if ($result['ok'] == false) {
            Log::error("slackチャンネル招待に失敗 メールアドレス:" . $email . " エラー詳細 :" . $result['error']);
        }

        curl_close($curl);
    }

    /**
     * slack_idを取得
     */
    public function lookupByEmail($token, $email)
    {
        $base_url = "https://slack.com/api/users.lookupByEmail?token={$token}&email={$email}";

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $base_url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $result = json_decode($response, true);

        curl_close($curl);

        if ($result['ok'] == true) {
            return $result['user']['id'];
        } else {
            return null;
        }
    }
}
