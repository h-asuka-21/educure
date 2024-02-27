<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\AbstractUserController;
use App\Services\CurriculumService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurriculumController extends AbstractUserController
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

    public function autocompleteWithCompanyId(Request $request)
    {
        $company_id = $this->getCompanyId();
        return response()->json($this->curriculum_service->getAutocompleteWithCompanyId($company_id));
    }

    /**
     * 一覧表示
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $curriculum_list = $this->curriculum_service->getList($this->getCompanyId());
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
}
