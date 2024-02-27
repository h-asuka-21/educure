<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\AbstractUserController;
use App\Services\TestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class TestController extends AbstractUserController
{
    const CONTROLLER_NAME = 'テスト';
    private TestService $testService;

    /**
     * TestController constructor.
     * @param TestService $testService
     */
    public function __construct(
        TestService $testService
    )
    {
        $this->testService = $testService;
        parent::__construct();
    }

    public function index(Request $request)
    {
    }

    public function show(Request $request, int $id)
    {
        $result = $this->testService->getDetail($id);
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
        $action_string = self::CONTROLLER_NAME . 'の登録';
        try {
            $this->testService->save($request);
            return $this->successResponse($action_string);
        } catch (\Exception $e) {
            return $this->errorResponse('', $e->getMessage(), null, $e->getCode() !== 0 ? $e->getCode() : 500);
        }
    }

    /**
     * 更新処理
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $action_string = self::CONTROLLER_NAME . 'の更新';
        try {
            $this->testService->save($request, $id);
            return $this->successResponse($action_string);
        } catch (\Exception $e) {
            return $this->errorResponse('', $e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * @param int $test_id
     * @return mixed
     */
    public function getStudentsByTestId(int $test_id)
    {
        return $this->testService->getStudentsByTestId($test_id);
    }

    public function autocomplete(Request $request)
    {
        try {
            return response()->json($this->testService->getAutocomplete($request->type));
        } catch (\Exception $e) {
            return $this->errorResponse('', $e->getMessage(), null, $e->getCode());
        }
    }
}
