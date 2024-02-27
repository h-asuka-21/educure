<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\AbstractUserController;
use App\Services\CurriculumService;
use App\Services\ProgressService;
use App\Services\ReservationService;
use App\Services\StudentService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class ProgressController extends AbstractUserController
{
    private ProgressService $progress;
    private ReservationService $reservation;
    private CurriculumService $curriculum;
    private StudentService $student;

    public function __construct(
        ProgressService $progress,
        ReservationService $reservation,
        CurriculumService $curriculum,
        StudentService $student
    )
    {
        $this->progress = $progress;
        $this->reservation = $reservation;
        $this->curriculum = $curriculum;
        $this->student = $student;
        parent::__construct();
    }

    public function getUsersAndProgresses(Request $request)
    {
        try {
            $company_id = $this->getCompanyId();
            return response()->json($this->progress->getUserProgresses($company_id));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            return $this->errorResponse('データの取得');
        }
    }

    public function getGraphData(Request $request, int $id): JsonResponse
    {
        try {
            $result = $this->curriculum->getProgress($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return $this->errorResponse('', '', null, 401);
        }
    }

    public function getProgressList(Request $request, int $id)
    {
        try {
            $student = $this->student->getById($id);
            $result = [];
            if (!empty($student['course_id'])) {
                $result = $this->curriculum->getStudentProgressHistory($student['course_id'], $student['id']);
            }
            return response()->json($result);
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得', $e->getMessage());
        }

    }


}
