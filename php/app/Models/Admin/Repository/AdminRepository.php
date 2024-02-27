<?php


namespace App\Models\Admin\Repository;

use App\Models\AbstractRepository;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminRepository extends AbstractRepository implements AdminRepositoryInterface
{
    /**
     * @param int $company_id
     * @return array
     */
    public function getList(int $company_id): array
    {
        $query = request()->query();
        return Admin::query()
            ->where('company_id', $company_id)
            ->paginate($this->getPerPage($query))
            ->toArray();
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        $query = request()->query();
        return Admin::query()->find($id);
    }

    /**
     * @param Admin $admin
     * @return Admin|null
     * @throws \Exception
     */
    public function save(Admin $admin): ?Admin
    {
        try {
            return DB::transaction(function () use ($admin) {
                return Admin::query()->updateOrCreate(
                    ['id' => $admin->id],
                    [
                        'name' => $admin->name,
                        'name_kana' => $admin->name_kana,
                        'password' => $admin->password,
                        'email' => $admin->email,
                    ]
                );
            });
        } catch (\Exception $e) {
            Log::error($e);
            throw new \Exception('処理に失敗しました。しばらくしてからもう一度お試しください。', 500);
        }
    }
}
