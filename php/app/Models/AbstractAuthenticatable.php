<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class AbstractAuthenticatable
 * ログインなどの認証に使用するモデル（User,Student,Admin）などはこのクラスを継承する
 * @package App\Models
 */
abstract class AbstractAuthenticatable extends Authenticatable
{

}
