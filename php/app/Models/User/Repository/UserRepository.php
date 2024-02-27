<?php


namespace App\Models\User\Repository;

use App\Models\AbstractRepository;
use App\Models\Student\Student;
use App\Models\User\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    protected array $searchable = [
        'users.name' => 'like',
        'users.email' => 'like',
    ];
    protected string $tbl_name = 'users';

    protected array $sortable = [
        'name' => 'users.name',
        'email' => 'users.email',
        'role' => 'users.role',
    ];

    /**
     * @param int|null $company_id
     * @param bool $admin
     * @return array
     */
    public function getList(?int $company_id, bool $admin): array
    {
        $query = request()->query();
        $result = User::query();
        if (!empty($company_id)) {
            $result->where('company_id', $company_id);
        }
        if (!$admin) {
            $result->where('role', 0);
        }
        return $result
            ->where(function (Builder $builder) use ($query) {
                $this->search($builder, $query);
            })
            ->orderByRaw($this->setOrder())
            ->paginate($this->getPerPage($query))
            ->toArray();
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return User::query()->find($id);
    }


    /**
     * @param User $user
     * @return bool
     */
    public function save(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            User::updateOrCreate(
                ['id' => $user->id],
                [
                    'company_id' => $user->company_id,
                    'name' => $user->name,
                    'name_kana' => $user->name_kana,
                    'email' => $user->email,
                    'password' => $user->password,
                    'role' => $user->role ?? 0
                ]
            );
            return true;
        });
    }

    /**
     * @param int $company_id
     * @return int
     */
    public function getCountByCompanyId(int $company_id)
    {
        return User::query()
            ->where('company_id', $company_id)
            ->count();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return User::find($id)->delete();
    }

    /**
     *
     * @param int $company_id
     * @return array
     */
    public function getIdList(int $company_id): array
    {
        return User::query()->select(['id as value', 'name as text'])
            ->where('company_id', $company_id)
            ->get()
            ->toArray();
    }


    public function getAdminRoleUserByCompanyId(int $company_id): ?User
    {
        return User::query()
            ->where('company_id', $company_id)
            ->where('role', 1)
            ->first();
    }
}
