<?php


namespace App\Http\Middleware\Authenticate;


use App\Http\Middleware\AbstractAuthenticate;
use App\Models\Student\Student;
use Illuminate\Contracts\Auth\Factory as Auth;

class StudentAuthenticate extends AbstractAuthenticate
{
    public function __construct(Auth $auth)
    {
        $this->guard = 'student';
        $this->cookie_key = 'student_token';
        parent::__construct($auth);
    }

    protected function getUser($id)
    {
        return Student::query()->find($id);
    }
}
