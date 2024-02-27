<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AbstractAdminController;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class ReserveController extends AbstractAdminController
{
    private ReservationService $reservation;

    public function __construct(
        ReservationService $reservation
    )
    {
        $this->reservation = $reservation;
        parent::__construct();
    }


    public function getStudentPerDate(Request $request)
    {
        try {
            return response()->json($this->reservation->getReservedStudentsPerDate($request, $request->company_id));
        } catch (UnauthorizedException $e) {
            return $this->errorResponse('', '', null, 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('データの取得', $e->getMessage());
        }
    }
}
