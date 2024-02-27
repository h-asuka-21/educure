<?php


namespace App\Services;


use App\Models\Admin\Admin;
use App\Models\Admin\Repository\AdminRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminService extends AbstractService
{

    private AdminRepositoryInterface $admin;

    public function __construct(
        AdminRepositoryInterface $admin
    )
    {
        $this->admin = $admin;
    }

    public function getList(?int $company_id): array
    {
        // TODO: Implement getList() method.
    }

    public function getById(int $id): ?array
    {
        $ret = $this->admin->find($id);
        return $ret !== null ? $ret->toArray() : null;
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return Admin|null
     * @throws \Exception
     */
    public function save(Request $request, ?int $id = null): ?Admin
    {
        return $this->admin->save($this->getModelAndSetParam($request, $id));
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return Admin
     * @throws \Exception
     */
    private function getModelAndSetParam(Request $request, ?int $id): Admin
    {
        $admin = new Admin();
        if ($id || $request->id) {
            /** @var  $admin Admin */
            $admin = $this->admin->find($id ?? $request->id);
            if ($request->password_change) {
                // 現在のパスワードが間違っている場合ここで例外を返す
                $this->checkPassword($request->current_password, $admin->password);
                $admin->password = Hash::make($request->new_password);
            }
        } else {
            // 新規作成時
            $admin->password = Hash::make($request->password);
        }
        $admin->name = $request->name;
        $admin->name_kana = $request->name_kana;
        $admin->email = $request->email;
        return $admin;
    }
}
