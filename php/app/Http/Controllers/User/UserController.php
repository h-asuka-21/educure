<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\AbstractUserController;
use Mockery\Exception;

class UserController extends AbstractUserController
{
    private const CONTROLLER_NAME = '講師';

    private UserService $user_service;

    /**
     * UserController constructor.
     * @param UserService $user_service
     */
    public function __construct
    (
        UserService $user_service
    )
    {
        $this->user_service = $user_service;
        parent::__construct();
    }

    /**
     * 詳細表示
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user_detail = $this->user_service->getList($id);
        return response()->json($user_detail);
    }

    /**
     * 登録処理
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        if ($this->user_service->createUser($request)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を登録');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の登録');
    }

    /**
     * 更新処理
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $this->user_service->updateUser($request, (int)$id);
            return $this->successResponse(self::CONTROLLER_NAME . 'を更新');
        } catch (Exception $e) {
            return $this->errorResponse(self::CONTROLLER_NAME . 'の更新', $e->getMessage());
        }
    }

    /**
     * 削除処理
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if ($this->user_service->deleteUser($id)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を削除');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の削除');
    }

    /**
     * オートコンプリート用講師リスト取得
     *
     * @return JsonResponse
     */
    public function autocomplete(): JsonResponse
    {
        $company_id = $this->getCompanyId();
        $schedule_list = $this->user_service->getUserIdList($company_id);
        return response()->json($schedule_list);
    }

}
