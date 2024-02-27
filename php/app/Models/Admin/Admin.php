<?php

namespace App\Models\Admin;

use App\Models\AbstractAuthenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class Admin
 * @package App\Models\Admin
 * @property int $id
 * @property string $name
 * @property string $name_kana
 * @property string $email
 * @property string $password
 * @property string created_at
 * @property string updated_at
 */
class Admin extends AbstractAuthenticatable implements JWTSubject
{
    protected $fillable = [
        'name',
        'name_kana',
        'password',
        'email',
    ];

    protected $hidden = [
        'password'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_kana' => $this->name_kana,
            'email' => $this->email,
        ];
    }
}
