<?php


namespace App\Models\User\Repository;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;

interface UserRepositoryInterface
{
    public function getList(int $company_id, bool $admin): array;

    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id);


    /**
     * @param User $user
     * @return bool
     */
    public function save(User $user): bool;

    /**
     * @param int $company_id
     * @return mixed
     */
    public function getCountByCompanyId(int $company_id);

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @param int $company_id
     * @return array
     */
    public function getIdList(int $company_id): array;


    public function getAdminRoleUserByCompanyId(int $company_id): ?User;
}
