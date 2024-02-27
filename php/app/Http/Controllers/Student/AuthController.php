<?php


namespace App\Http\Controllers\Student;


use App\Http\Controllers\AbstractAuthController;
use App\Services\StudentService;

class AuthController extends AbstractAuthController
{
    private $student_service;

    public function __construct(
        StudentService $student_service
    )
    {
        $this->guard = 'student';
        $this->cookie_key = 'student_token';
        $this->middleware_name = 'student.auth';
        $this->student_service = $student_service;
        parent::__construct();
    }

    protected function getUser(int $id): array
    {
        return $this->student_service->getById($id);
    }
}
