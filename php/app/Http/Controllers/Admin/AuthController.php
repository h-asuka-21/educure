<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AbstractAuthController;
use App\Services\AdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends AbstractAuthController
{
    private $admin_service;

    public function __construct(
        AdminService $admin_service
    )
    {
        $this->guard = 'admin';
        $this->cookie_key = 'admin_token';
        $this->middleware_name = 'admin.auth';
        $this->admin_service = $admin_service;

        parent::__construct();
    }

    protected function getUser(int $id): array
    {
        return $this->admin_service->getById($id);
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $this->admin_service->save($request);
            return $this->successResponse('プロフィールの更新');
        } catch (\Exception $e) {
            return $this->errorResponse('プロフィールの更新', $e->getMessage());
        }
    }
}
