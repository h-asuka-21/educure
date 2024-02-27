<?php

namespace App\Models\Company;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Company
 * @package App\Models\Company
 * @property int $id
 * @property string $company_code
 * @property string $name
 * @property string $industry
 * @property string $number_of_employees
 * @property string $year_of_establishment
 * @property string $average_age
 * @property string $slack_token
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 */
class Company extends AbstractModel
{
    use SoftDeletes;

    protected $fillable = [
        'company_code',
        'name',
        'industry',
        'number_of_employees',
        'year_of_establishment',
        'average_age',
        'slack_token',
    ];
    //
}
