<?php


namespace App\Models\Admin\Repository;

use App\Models\Admin\Admin;

interface AdminRepositoryInterface
{
    public function getList(int $company_id): array;

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id);

    public function save(Admin $admin): ?Admin;
}
