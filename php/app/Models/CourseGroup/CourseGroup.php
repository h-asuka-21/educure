<?php


namespace App\Models\CourseGroup;


use App\Models\AbstractModel;

/**
 * Class CourseGroup
 * @property int|null $id
 * @property int $company_id
 * @property int $course_id
 * @property string $created_at
 * @property string $updated_at
 * @package App\Models\CourseGroup
 */
class CourseGroup extends AbstractModel
{
    protected $table = 'course_groups';
    protected $fillable = [
        'company_id',
        'course_id',
    ];
}
