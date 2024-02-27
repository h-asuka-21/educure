<?php


namespace App\Http\Controllers\Student;


use App\Http\Controllers\AbstractStudentController;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Mockery\Exception;

class StudentController extends AbstractStudentController
{
    private StudentService $student;

    public function __construct(
        StudentService $student
    )
    {
        $this->student = $student;
        parent::__construct();
    }

    public function progress(Request $request): JsonResponse
    {
        try {
            $company_id = $this->getCompanyId();
            $id = $this->getId();
            $result = $this->student->getProgress($id, $company_id);
            return response()->json($result);
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得');
        }
    }

    public function evaluation(Request $request): JsonResponse
    {
        try {
            $id = $this->getId();
            return response()->json($this->student->getEvaluation($id));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得');
        }

    }

    public function teacher_evalution(Request $request): JsonResponse
    {
        try {
            $id = $this->getId();
            return response()->json($this->student->getTeacherEvaluationData($id));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得', $e->getMessage(), null, $e->getCode() || 500);
        }
    }

    /**
     * 更新処理
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $action = 'プロフィールの更新';
        try {
            $company_id = $this->getCompanyId();
            $this->student->save($request, $this->getCompanyId(), $this->getId());
            return $this->successResponse($action);
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            return $this->errorResponse('', $e->getMessage());
        }
    }

    public function evaluationRanking(Request $request): JsonResponse
    {
        try {
            $company_id = $this->getCompanyId();
            return response()->json($this->student->getEvaluationRanking($company_id));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得');
        }

    }

    public function selfRanking(Request $request): JsonResponse
    {
        try {
            $student_id = $this->getId();
            $company_id = $this->getCompanyId();
            return response()->json($this->student->getselfRanking($student_id, $company_id));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得');
        }

    }


}
