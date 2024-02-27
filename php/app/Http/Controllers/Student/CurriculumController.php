<?php


namespace App\Http\Controllers\Student;


use App\Http\Controllers\AbstractStudentController;
use App\Services\CurriculumService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class CurriculumController extends AbstractStudentController
{
    private CurriculumService $curriculum;

    public function __construct(
        CurriculumService $curriculum
    )
    {
        $this->curriculum = $curriculum;
        parent::__construct();
    }

    public function index(Request $request)
    {
        try {
            $id = $this->getId();
            return response()->json($this->curriculum->getCurriculumsAndProgressesFromStudentId($id, false));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得');
        }

    }

    public function show(Request $request, int $id)
    {
    }

}
