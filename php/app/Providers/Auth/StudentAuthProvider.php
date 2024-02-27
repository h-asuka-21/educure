<?php


namespace App\Providers\Auth;

use App\Providers\AbstractAuthProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class StudentAuthProvider extends AbstractAuthProvider
{
    protected string $table_name = 'students';
}
