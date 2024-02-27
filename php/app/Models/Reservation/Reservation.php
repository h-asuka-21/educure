<?php

namespace App\Models\Reservation;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int|null $id
 * @property int $student_id
 * @property int $schedule_id
 * @property string $start_time
 * @property string $end_time
 * @property int $attendance_flg
 * @property string|null $attendance_time
 * @property string|null $reason
 * @property int|null $teacher_evaluation
 * @property string|null $evaluation_reason
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Reservation extends AbstractModel
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'schedule_id',
        'start_time',
        'end_time',
        'available_limit',
        'reason',
        'teacher_evaluation',
        'evaluation_reason',
        'attendance_flg',
        'attendance_time'
    ];
}
