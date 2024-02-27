<?php


namespace App\Http\Middleware\Authenticate;


use App\Http\Middleware\AbstractAuthenticate;
use Illuminate\Contracts\Auth\Factory as Auth;
use App\Models\Admin\Admin;

class AdminAuthenticate extends AbstractAuthenticate
{
    public function __construct(Auth $auth)
    {
        $this->guard = 'admin';
        $this->cookie_key = 'admin_token';
        parent::__construct($auth);
    }

    protected function getUser($id)
    {
        return Admin::query()->find($id);
    }
}
