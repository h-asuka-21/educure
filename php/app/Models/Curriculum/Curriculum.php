<?php

namespace App\Models\Curriculum;

use App\Models\AbstractModel;


/**
 * @package App\Models\Question
 * @property int|null $id
 * @property string $name
 * @property string $zip
 * @property string $zip_name
 * @property int|null $test_id
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Curriculum extends AbstractModel
{

    /**
     * 使用テーブル
     *
     * @var string
     */
    protected $table = 'curriculums';

    protected $fillable = [
        'name',
        'zip',
        'zip_name',
        'test_id'
    ];
}
