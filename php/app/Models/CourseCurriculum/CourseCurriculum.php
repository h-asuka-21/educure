<?php


namespace App\Models\CourseCurriculum;


use App\Models\AbstractModel;

/**
 * Class CourseCurriculum
 * @property int|null $id
 * @property int $course_id
 * @property int $curriculum_id
 * @property int $order
 * @property string $created_at
 * @property string $updated_at
 * @package App\Models\CourseCurriculum
 */
class CourseCurriculum extends AbstractModel
{
    protected $table = 'course_curriculums';
    protected $fillable = [
        'course_id',
        'curriculum_id',
        'order',
    ];
}
