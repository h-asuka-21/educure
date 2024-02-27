<?php

namespace App\Models\Progress;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int|null $id
 * @property int $student_id
 * @property int $step_id
 * @property int|null $percent
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $reservation_id
 * @property string $date
 */
class Progress extends AbstractModel
{
    use SoftDeletes;

    protected $table = 'progresses';
    protected $fillable = [
        'student_id',
        'step_id',
        'percent',
        'progress_status',
        'application_flg',
        'reservation_id',
        'date',
        'report_time'
    ];
}
