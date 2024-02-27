<?php


namespace App\Http\Middleware\Authenticate;


use App\Http\Middleware\AbstractAuthenticate;
use App\Models\User\User;
use Illuminate\Contracts\Auth\Factory as Auth;

class UserAuthenticate extends AbstractAuthenticate
{
    public function __construct(Auth $auth)
    {
        $this->guard = 'user';
        $this->cookie_key = 'user_token';
        parent::__construct($auth);
    }

    protected function getUser($id)
    {
        return User::query()->find($id);
    }
}
