<?php


namespace App\Http\Controllers\Student;


use App\Http\Controllers\AbstractStudentController;
use App\Models\Test\Repository\TestRepository;
use App\Services\CurriculumService;
use App\Services\ProgressService;
use App\Services\TestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;


class ProgressController extends AbstractStudentController
{
    private const CONTROLLER_NAME = '進捗報告';

    private ProgressService $progress;
    private CurriculumService $curriculum;
    private TestService $test;

    public function __construct(
        ProgressService $progress,
        CurriculumService $curriculum,
        TestService $test
    )
    {
        $this->progress = $progress;
        $this->curriculum = $curriculum;
        $this->test = $test;
        parent::__construct();
    }

    public function getGraphData(Request $request): JsonResponse
    {
        try {
            $id = $this->getId();
            $result = $this->curriculum->getProgress($id);
            return response()->json($result);
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得');
        }
    }

    public function getCurrent(Request $request)
    {
        try {
            $id = $this->getId();
            $result = $this->curriculum->getCurrentCurriculumAndStepByStudentId($id);
            return response()->json($result);
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得');
        }
    }

    public function getLatestUnAnsweredTest(Request $request)
    {
        try {
            $course_id = $this->getCourseId();
            $student_id = $this->getId();
            $result = $this->test->getEnableTestByCourseAndStudentId($course_id, $student_id);
            return response()->json($result);
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得', $e->getMessage());
        }
    }

    public function getProgressList(Request $request)
    {
        try {
            $course_id = $this->getCourseId();
            $student_id = $this->getId();
            $result = $this->curriculum->getStudentProgressHistory($course_id, $student_id);
            return response()->json($result);
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得', $e->getMessage());
        }

    }

    public function saveStudentProgress(Request $request)
    {
        $student_id = $this->getId();
        if ($this->progress->saveStudentProgress($student_id)) {
            return $this->successResponse(self::CONTROLLER_NAME . 'を登録');
        }
        return $this->errorResponse(self::CONTROLLER_NAME . 'の登録');
    }

}
