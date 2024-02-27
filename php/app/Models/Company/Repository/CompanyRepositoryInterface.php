<?php


namespace App\Models\Company\Repository;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Builder;

interface CompanyRepositoryInterface
{
    public function getList(?int $company_id): array;

    public function getCompanyList(): array;

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id);

    public function getCompanyIdByToken(string $token);

    /**
     * @param Company $company
     * @return \Illuminate\Database\Eloquent\Model|Builder
     */
    public function save(Company $company);

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    public function getAutocompleteItem(): array;

    public function getAllIds(): array;
}
