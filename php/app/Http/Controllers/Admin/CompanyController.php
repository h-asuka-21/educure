<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\AbstractAdminController;

class CompanyController extends AbstractAdminController
{
    private const CONTROLLER_NAME = '企業';

    private CompanyService $company_service;

    /**
     * CompanyController constructor.
     * @param CompanyService $company_service
     */
    public function __construct
    (
        CompanyService $company_service
    )
    {
        $this->company_service = $company_service;
        parent::__construct();
    }

    /**
     * 一覧表示
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $company_list = $this->company_service->getList(null);
        return response()->json($company_list);
    }

    /**
     * 詳細表示
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $company_detail = $this->company_service->getById($id);
        return response()->json($company_detail);
    }

    /**
     * 登録処理
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $result = $this->company_service->createCompany($request);
        if ($result === true) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を登録');
        }
        if ($result === false) {
            return $this->errorResponse(self::CONTROLLER_NAME . 'の登録');
        }
        return $this->errorResponse('', $result);
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
        if ($this->company_service->updateCompany($request, (int)$id)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を更新');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の更新');
    }

    /**
     * 削除処理
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if ($this->company_service->deleteCompany($id)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を削除');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の削除');
    }

    public function autocomplete(Request $request)
    {
        return response()->json($this->company_service->getAutocomplete());
    }


}
