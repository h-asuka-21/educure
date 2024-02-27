<?php


namespace App\Services;


use App\Models\Company\Company;
use App\Models\CourseGroup\CourseGroup;
use App\Models\CourseGroup\Repository\CourseGroupRepositoryInterface;
use App\Models\SlackChannel\SlackChannel;
use App\Models\Company\Repository\CompanyRepositoryInterface;
use App\Models\User\Repository\UserRepositoryInterface;
use App\Models\Student\Repository\StudentRepositoryInterface;
use App\Models\SlackChannel\Repository\SlackChannelRepositoryInterface;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyService extends AbstractService
{
    private CompanyRepositoryInterface $company;
    private UserRepositoryInterface $user;
    private StudentRepositoryInterface $student;
    private SlackChannelRepositoryInterface $slack_channel;
    private CourseGroupRepositoryInterface $course_group;

    public function __construct(
        CompanyRepositoryInterface $company,
        UserRepositoryInterface $user,
        StudentRepositoryInterface $student,
        SlackChannelRepositoryInterface $slack_channel,
        CourseGroupRepositoryInterface $course_group
    )
    {
        $this->company = $company;
        $this->user = $user;
        $this->student = $student;
        $this->slack_channel = $slack_channel;
        $this->course_group = $course_group;
    }

    /**
     * @param int|null $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        return $this->company->getList($company_id);
    }

    /**
     * @return array
     */
    public function getCompanyList(): array
    {
        return $this->company->getCompanyList();
    }

    public function getCompanyIdByToken(string $token)
    {
        $ret = $this->company->getCompanyIdByToken($token);
        return $ret !== null ? $ret->toArray() : null;
    }

    public function getSlackChannelByToken(array $company_ids)
    {
        $ret = $this->slack_channel->getSlackChannelbyToken($company_ids);
        return $ret !== null ? $ret->toArray() : null;
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $ret = $this->company->find($id);
        $course_ids = $this->course_group->getCoursesByCompanyId($id)->toArray();
        $courses = [];
        foreach ($course_ids as $key => $course_id) {
            $courses[] = $course_id['id'];
        }
        $ret->courses = $courses;
        return $ret !== null ? $ret->toArray() : null;
    }

    /**
     * @param $request
     * @return bool
     */
    public function createCompany($request)
    {
        try {
            DB::beginTransaction();
            // 企業情報の作成
            $company = new Company();
            $company->name = $request->name;
            $company->company_code = $request->company_code;
            $company->industry = $request->industry;
            $company->number_of_employees = $request->number_of_employees;
            $company->year_of_establishment = $request->year_of_establishment;
            $company->average_age = $request->average_age;
            $company->slack_token = $request->slack_token;
            /** @var  $company Company */
            $company = $this->company->save($company);

            // 管理ユーザーの作成
            if ($request->create_user) {
                $user = new User();
                $user->company_id = $company->id;
                $user->name = 'stlaun管理者';
                $user->name_kana = 'ストロンカンリシャ';
                $user->email = config('master_user_email', 'stlaun@stlaun.jp');
                $user->password = bcrypt(config('master_user_password', 'password'));
                $user->role = 1;
                $this->user->save($user);
            }

            // コースグループ作成
            foreach ($request->courses as $value) {
                $course_group = new CourseGroup();
                $course_group->course_id = $value;
                $course_group->company_id = $company->id;
                $this->course_group->save($course_group);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            if (strpos($e->getMessage(), 'companies_company_code_unique')) {
                // 企業コードのユニークに引っかかった場合
                return '企業コードが重複しています。異なる企業コードを設定して再度登録してください';
            }
            return false;
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return bool
     */
    public function updateCompany(Request $request, int $id)
    {
        try {
            DB::beginTransaction();
            $target_company = $this->company->find($id);

            if ($target_company === null) {
                return false;
            }

            $target_company->name = $request->name;
            $target_company->company_code = $request->company_code;
            $target_company->industry = $request->industry;
            $target_company->number_of_employees = $request->number_of_employees;
            $target_company->year_of_establishment = $request->year_of_establishment;
            $target_company->average_age = $request->average_age;
            $target_company->slack_token = $request->slack_token;
            $this->company->save($target_company);

            // コースグループ削除
            $this->course_group->deleteByCompanyId($id);

            // コースグループ作成
            foreach ($request->courses as $value) {
                $course_group = new CourseGroup();
                $course_group->course_id = $value;
                $course_group->company_id = $id;
                $this->course_group->save($course_group);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            if (strpos($e->getMessage(), 'companies_company_code_unique')) {
                // 企業コードのユニークに引っかかった場合
                return '企業コードが重複しています。異なる企業コードを設定して再度登録してください';
            }
            return false;
        }

    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteCompany(int $id)
    {
        $target_company = $this->company->find($id);
        if ($target_company === null) {
            return false;
        }
        return $this->company->delete($id);
    }

    public function getAutocomplete()
    {
        return $this->company->getAutocompleteItem();
    }

    /**
     * slackチャンネル情報を取得
     */
    public function getSlackChannel($company_id)
    {
        $ret = $this->slack_channel->getSlackChannel($company_id);
        return $ret !== null ? $ret->toArray() : null;
    }

    /**
     * slackチャンネルIDを格納
     * @param $request
     *
     * @return bool
     */
    public function createSlackChannel($request)
    {
        try {
            DB::beginTransaction();
            // slackチャンネルの作成
            $slack_channel = new SlackChannel();
            $slack_channel->company_id = $request['company_id'];
            $slack_channel->channel_id = $request['channel_id'];
            /** @var  $company Company */
            $slack_channel = $this->slack_channel->save($slack_channel);

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
    public function deleteSlackChannel()
    {
        return $this->slack_channel->delete();
    }
}
