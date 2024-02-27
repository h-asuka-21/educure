<?php

namespace App\Models\Student;

use App\Models\AbstractAuthenticatable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 生徒クラス
 * @property int $id
 * @property int $company_id
 * @property string $name 名前
 * @property string $name_kana 名前カナ
 * @property string $password パスワード
 * @property string $email メールアドレス
 * @property int $course_id コースID
 * @property string $start_date 受講開始日
 * @property string $end_date 受講終了日
 * @property string $birthday 誕生日
 * @property int $gender 性別
 * @property int $academic_type 最終学歴
 * @property int $birthplace 出身地
 * @property int $working_history 社会人歴
 * @property int $former_job_type 前職業種
 * @property int $former_job_status 前職雇用形態
 * @property int $change_job_count 転職回数
 * @property int $national_qualification_flg 国家資格有無
 * @property int $qualification_flg 資格有無
 * @property int $club_type 部活
 * @property int $after_graduation_flg 卒業後
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @package App\Models\Student
 */
class Student extends AbstractAuthenticatable implements JWTSubject
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'name_kana',
        'password',
        'email',
        'course_id',
        'start_date',
        'end_date',
        'birthday',
        'gender',
        'academic_type',
        'birthplace',
        'working_history',
        'former_job_type',
        'former_job_status',
        'change_job_count',
        'national_qualification_flg',
        'qualification_flg',
        'club_type',
        'after_graduation_flg',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'name_kana' => $this->name_kana,
            'email' => $this->email,
            'course_id' => $this->course_id,
        ];
    }
}
