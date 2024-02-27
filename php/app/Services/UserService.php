<?php


namespace App\Services;

use App\Models\Company\Company;
use App\Models\Company\Repository\CompanyRepositoryInterface;
use App\Models\User\User;
use App\Models\User\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class UserService extends AbstractService
{
    private UserRepositoryInterface $user;
    private CompanyRepositoryInterface $company;

    /**
     * UserService constructor.
     * @param UserRepositoryInterface $user
     */
    public function __construct(
        UserRepositoryInterface $user,
        CompanyRepositoryInterface $company
    )
    {
        $this->user = $user;
        $this->company = $company;
    }

    /**
     * @param int|null $company_id
     * @param bool $admin
     * @return array
     */
    public function getList(?int $company_id, bool $admin = false): array
    {
        return $this->user->getList($company_id, $admin);
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $ret = $this->user->find($id);
        if ($ret === null) {
            throw new \Exception('ユーザーが見つかりませんでした');
        }
        /** @var  $company Company */
        $company = $this->company->find($ret->company_id);
        $ret->setAttribute('company_name', $company->name);
        return $ret->toArray();
    }

    /**
     * @param $request
     * @return bool
     */
    public function createUser($request)
    {
        $user = new User();
        $user->company_id = $request->company_id;
        $user->name = $request->name;
        $user->name_kana = $request->name_kana;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        return $this->user->save($user);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return bool
     */
    public function updateUser(Request $request, int $id)
    {
        /** @var  $target_user User */
        $target_user = $this->user->find($id);

        if ($target_user === null) {
            return false;
        }
        if ($request->password_change) {
            // 現在のパスワードが間違っている場合ここで例外を返す
            $this->checkPassword($request->current_password, $target_user->password);
            $target_user->password = Hash::make($request->new_password);
        }

        $target_user->name = $request->name;
        $target_user->name_kana = $request->name_kana;
        $target_user->email = $request->email;
        if (isset($request->password) && !empty($request->password)) {
            // パスワード変更がある場合
            $target_user->password = Hash::make($request->password);
        }

        return $this->user->save($target_user);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id)
    {
        $target_user = $this->user->find($id);
        if ($target_user === null) {
            return false;
        }
        return $this->user->delete($id);
    }

    /**
     * セレクトボックス用クライアントリスト取得
     *
     * @param int $company_id
     * @return array
     */
    public function getUserIdList(int $company_id): array
    {
        return $this->user->getIdList($company_id);
    }

    public function getAdminUserByCompanyId(int $company_id): ?array
    {
        $user = $this->user->getAdminRoleUserByCompanyId($company_id);
        if ($user === null) {
            throw new \Exception('対象の企業に管理ユーザーが登録されていません。管理ユーザーの登録を行ってください。');
        }
        return $user->toArray();
    }
}
