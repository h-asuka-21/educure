<?php

namespace App\Models\Step;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @package App\Models\Step
 * @property int $id
 * @property int $curriculum_id
 * @property string $name
 * @property string $content
 * @property string $image
 * @property int $target_days
 * @property int $deadline_days
 * @property int $order
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 *
 */
class Step extends AbstractModel
{
    use SoftDeletes;

    protected $fillable = [
        'curriculum_id',
        'name',
        'content',
        'image',
        'target_days',
        'deadline_days',
        'order'
    ];
}
