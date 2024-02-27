<?php

namespace App\Models\MissingEvaluationItem;

use App\Models\AbstractModel;

/**
 * @property int|null $id
 * @property int $student_id
 * @property int $missing_type
 * @property string $reason
 * @property string $created_at
 * @property string $updated_at
 */
class MissingEvaluationItem extends AbstractModel
{
    protected $table = 'missing_evaluation_items';


    public const TYPE = [
        'teacher_score' => 0, // 講師評価
        'sales_score' => 1, // 営業評価
        'comprehension_score' => 2, // 理解度
        'think_score' => 3, // 思考力
        'attendance_score' => 4, // 出席率
        'report_score' => 5, // 報告率
        'progress_score' => 6, // 進捗率
    ];

    protected $fillable = [
        'student_id',
        'missing_type',
        'reason'
    ];
}
