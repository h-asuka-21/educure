<?php


namespace App\Providers\Auth;

use App\Providers\AbstractAuthProvider;

class UserAuthProvider extends AbstractAuthProvider
{
    protected string $table_name = 'users';
}
