<?php


namespace App\Http\Controllers\Student;


use App\Http\Controllers\AbstractStudentController;
use App\Services\ScoreService;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class ScoreController extends AbstractStudentController
{
    const CONTROLLER_NAME = 'スコア';
    private ScoreService $scoreService;

    /**
     * ScoreController constructor.
     * @param ScoreService $scoreService
     */
    public function __construct(
        ScoreService $scoreService
    )
    {
        $this->scoreService = $scoreService;
        parent::__construct();
    }

    public function index(Request $request)
    {
        try {
            $student_id = $this->getId();
            return response()->json($this->scoreService->getList($student_id));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        }
    }

    public function show(Request $request, int $id)
    {
        $result = $this->scoreService->getDetail($id);
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
    public function store(Request $request, CookieJar $cookie)
    {
        try {
            // テスト中にログイントークンの期限が切れることを防ぐため、回答開始時点でトークンをリフレッシュする。
            $new_token = $this->reHashToken($request, $cookie);
            $student_id = $this->getId();
            return response()->json($this->scoreService->save($request, $student_id))->withCookie($new_token);
        } catch (\Exception $e) {
            return $this->errorResponse('', $e->getMessage(), null, $e->getCode() !== 0 ? $e->getCode() : 500);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $student_id = $this->getId();
            return response()->json($this->scoreService->save($request, $student_id, $id));
        } catch (\Exception $e) {
            return $this->errorResponse('', $e->getMessage(), null, $e->getCode() !== 0 ? $e->getCode() : 500);
        }

    }


}
