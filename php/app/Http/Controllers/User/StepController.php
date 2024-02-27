<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\AbstractUserController;
use App\Services\StepService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class StepController extends AbstractUserController
{

    private StepService $step;

    public function __construct(
        StepService $step
    )
    {
        $this->step = $step;
        parent::__construct();
    }

    public function autocomplete()
    {
        try {
            $company_id = $this->getCompanyId();
            return response()->json($this->step->getAutocomplete($company_id));
        } catch (UnauthorizedException $e) {
            return $this->authFailedResponse();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('コース一覧の取得');
        }
    }
}
