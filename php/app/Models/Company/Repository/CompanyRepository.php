<?php


namespace App\Models\Company\Repository;

use App\Models\AbstractRepository;
use App\Models\Company\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;


class CompanyRepository extends AbstractRepository implements CompanyRepositoryInterface
{
    protected array $searchable = [
        'companies.company_code' => 'like',
        'companies.name' => 'like',
    ];
    protected string $tbl_name = 'companies';
    protected array $sortable = [
        'name' => "companies.name",
        'created_at' => 'companies.created_at',
        'student_count' => 'student_count',
        'user_count' => 'user_count',
        'taking_student_count' => 'taking_student_count',
    ];

    /**
     * @param int $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        $now = Carbon::now();
        $query = request()->query();
        $result = Company::query()
            ->select([
                'companies.*',
                DB::raw(' (select count(id) from students where
                        company_id = companies.id
                        and students.deleted_at is null )as student_count'),
                DB::raw(' (select count(id) from users where
                        company_id = companies.id
                        and users.deleted_at is null )as user_count'),
                DB::raw(' (select count(id) from students where
                        company_id = companies.id
                        and after_graduation_flg = 0
                        and students.deleted_at is null )as taking_student_count'),
                DB::raw(" (select count(id) from students where
                        company_id = companies.id
                        and after_graduation_flg <> 0
                        and after_graduation_flg <> 4
                        and students.deleted_at is null
                        and end_date >= '{$now->format('Y-m-01')}'
                        and end_date <= '{$now->format('Y-m-t')}' )as graduated_this_month_count"),
                DB::raw(" (select count(id) from students where
                        company_id = companies.id
                        and after_graduation_flg = 4
                        and students.deleted_at is null
                        and end_date >= '{$now->format('Y-m-01')}'
                        and end_date <= '{$now->format('Y-m-t')}' )as retired_this_month_count"),
                DB::raw(" (select count(id) from students where
                        company_id = companies.id
                        and after_graduation_flg = 0
                        and students.deleted_at is null
                        and start_date >= '{$now->format('Y-m-01')}'
                        and start_date <= '{$now->format('Y-m-t')}' )as active_this_month_count"),
            ]);
        if (!is_null($company_id)) {
            $result->where('company_id', $company_id);
        }
        return $result
            ->where(function (Builder $builder) use ($query) {
                $this->search($builder, $query);
            })
            ->orderByRaw($this->setOrder())
            ->paginate($this->getPerPage($query))
            ->toArray();
    }

    public function getCompanyList(): array
    {
        $result = Company::get();
        return $result->toArray();
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return Company::query()->find($id);
    }


    public function getCompanyIdByToken(string $token)
    {
        return Company::query()
            ->select([
                'id',
            ])
            ->where('slack_token', $token)
            ->get();
    }


    /**
     * @param Company $company
     * @return \Illuminate\Database\Eloquent\Model|Builder
     */
    public function save(Company $company)
    {
        return DB::transaction(function () use ($company) {
            return Company::query()->updateOrCreate(
                ['id' => $company->id],
                [
                    'company_code' => $company->company_code,
                    'name' => $company->name,
                    'industry' => $company->industry,
                    'number_of_employees' => $company->number_of_employees,
                    'year_of_establishment' => $company->year_of_establishment,
                    'average_age' => $company->average_age,
                    'slack_token' => $company->slack_token,
                ]
            );
        });
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return Company::find($id)->delete();
    }

    public function getAutocompleteItem(): array
    {
        return Company::query()
            ->select([
                'id as value',
                'name as text'
            ])
            ->get()->toArray();

    }

    public function getAllIds(): array
    {
        return Company::query()
            ->get('id')->getQueryableIds();
    }
}
