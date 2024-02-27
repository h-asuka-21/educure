<?php

namespace App\Models\TeacherSchedule;

use App\Models\AbstractModel;


/**
 * Class TeacherSchedule
 * @package App\Models\TeacherSchedule
 * @property int $id
 * @property int $schedule_id
 * @property int $user_id
 * @property string created_at
 * @property string updated_at
 */
class TeacherSchedule extends AbstractModel
{
    protected $fillable = [
        'schedule_id',
        'user_id',
    ];
}
