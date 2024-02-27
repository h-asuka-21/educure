<?php

namespace App\Models\InterviewHistory;

use App\Models\AbstractModel;

/**
 * Class InterviewHistory
 * @package App\Models\InterviewHistory
 * @property int $id
 * @property int $student_id
 * @property int $sales_evaluation
 * @property string $evaluation_reason
 * @property int $user_id
 * @property string created_at
 * @property string updated_at
 */
class InterviewHistory extends AbstractModel
{
    protected $fillable = [
        'student_id',
        'sales_evaluation',
        'evaluation_reason',
        'user_id',
    ];
}
