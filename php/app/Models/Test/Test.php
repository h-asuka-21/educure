<?php

namespace App\Models\Test;

use App\Models\AbstractModel;


/**
 * Class Company
 * @package App\Models\Company
 * @property int $id
 * @property string $name
 * @property int $course_id
 * @property int $curriculum_id
 * @property int $test_type
 * @property int $test_time
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Test extends AbstractModel
{
    public const TYPE = [
        'curriculum' => 0,
        'comp' => 1,
        'cab' => 2
    ];
    protected $fillable = [
        'name',
        'test_type',
        'test_time'
    ];
}
