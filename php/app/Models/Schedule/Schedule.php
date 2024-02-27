<?php

namespace App\Models\Schedule;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Schedule
 * @package App\Models\Schedule
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string $date
 * @property string $start_time
 * @property string $end_time
 * @property string $available_limit
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 */
class Schedule extends AbstractModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'company_id',
        'date',
        'start_time',
        'end_time',
        'available_limit'
    ];
}
