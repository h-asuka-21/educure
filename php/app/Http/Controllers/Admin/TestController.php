<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AbstractAdminController;
use App\Services\TestService; // serviceを呼び出し。
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class TestController extends AbstractAdminController
{
    const CONTROLLER_NAME = 'テスト';
    private TestService $testService; // testServiceインスタンスを入れる用の変数をprivateで用意しておく。   

    /**
     * TestController constructor.
     * @param TestService $testService
     */
    public function __construct(TestService $testService) // インジェクションでインスタンス化する。引数の中でこのようにインスタンス化すると、本来親クラスのコンストラクタをインスタンス化してから使わないといけないとかそういう依存関係も解決してくれる。
    {
        $this->testService = $testService; // インスタンス変数にオブジェクトをぶっこむ
        parent::__construct();
    }

    public function index(Request $request):JsonResponse
    {
        // リクエストから company_id を取得
        $company_id = $request->input('company_id');

        // TestService の getList メソッドを呼び出し
        $test_list = $this->testService->getList($company_id);
        // JSON レスポンスを返す
        return response()->json($test_list);
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

    public function autocomplete(Request $request)
    {
        try {
            return response()->json($this->testService->getAutocomplete($request->type));
        } catch (\Exception $e) {
            return $this->errorResponse('', $e->getMessage(), null, $e->getCode());
        }
    }

}
