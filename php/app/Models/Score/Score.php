<?php

namespace App\Models\Score;

use App\Models\AbstractModel;


/**
 * Class Company
 * @package App\Models\Score
 * @property int $id
 * @property int $student_id
 * @property int $test_id
 * @property int $score
 * @property string $choices
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Score extends AbstractModel
{
    protected $fillable = [
        'student_id',
        'test_id',
        'score',
        'choices',
    ];
}
