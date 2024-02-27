<?php


namespace App\Models\Course;


use App\Models\AbstractModel;

/**
 * Class Course
 * @package App\Models\Course
 *
 * @property int|null $id
 * @property string $name
 * @property int|null $general_test_id
 * @property int|null $first_test_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 */
class Course extends AbstractModel
{
    protected $fillable = [
        'name',
        'general_test_id',
        'first_test_id',
    ];

}
