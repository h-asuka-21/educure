<?php

namespace App\Models\Report;
use App\Models\AbstractModel;
/**
 * Class Report
 * @package App\Models\Report
 * @property int $id
 * @property int $reservation_id
 * @property int $student_id
 * @property int $personal_evaluation
 * @property string $worked
 * @property string $note
 * @property string created_at
 * @property string updated_at
 */
class Report extends AbstractModel

{
    protected $fillable = [
        'reservation_id',
        'student_id',
        'personal_evaluation',
        'worked',
        'note',
    ];

}