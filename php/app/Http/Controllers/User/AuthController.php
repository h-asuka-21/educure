<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\AbstractAuthController;
use App\Services\UserService;

class AuthController extends AbstractAuthController
{
    private $user_service;

    public function __construct(
        UserService $user_service
    )
    {
        $this->guard = 'user';
        $this->cookie_key = 'user_token';
        $this->middleware_name = 'user.auth';
        $this->user_service = $user_service;

        parent::__construct();
    }

    protected function getUser(int $id): array
    {
        return $this->user_service->getById($id);
        // TODO: Implement getUser() method.
    }
}
