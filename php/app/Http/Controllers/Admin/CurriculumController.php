<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AbstractAdminController;
use App\Services\CurriculumService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurriculumController extends AbstractAdminController
{
    private const CONTROLLER_NAME = 'カリキュラム';

    private CurriculumService $curriculum_service;

    public function __construct(
        CurriculumService $curriculum_service
    )
    {
        $this->curriculum_service = $curriculum_service;
        parent::__construct();
    }


    public function autocomplete(Request $request)
    {
        return response()->json($this->curriculum_service->getAutocomplete());
    }

    /**
     * 一覧表示
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $curriculum_list = $this->curriculum_service->getList(null);
        return response()->json($curriculum_list);
    }

    /**
     * 詳細表示
     * @return JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $result = $this->curriculum_service->getDetail($id);
        if ($result) {
            return response()->json($result);
        }
        return $this->errorResponse('', '', null, 404);
    }

    /**
     * 登録処理
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        if ($this->curriculum_service->save($request)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を更新');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の更新');
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
        if ($this->curriculum_service->save($request, $id)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を更新');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の更新');
    }
}
