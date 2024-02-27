<?php

namespace App\Models\Question;

use App\Models\AbstractModel;


/**
 * @package App\Models\Question
 * @property int|null $id
 * @property string $name
 * @property int $test_id
 * @property string $content
 * @property string $image
 * @property string $choice1
 * @property string $choice2
 * @property string $choice3
 * @property string $choice4
 * @property int $answer
 * @property int $order
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Question extends AbstractModel
{
    protected $fillable = [
        'name',
        'test_id',
        'content',
        'image',
        'choice1',
        'choice2',
        'choice3',
        'choice4',
        'answer',
        'order'
    ];
}
