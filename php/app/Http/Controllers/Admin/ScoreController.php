<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AbstractAdminController;
use App\Services\ScoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class ScoreController extends AbstractAdminController
{
    private ScoreService $scores;

    public function __construct(
        ScoreService $scores
    )
    {
        $this->scores = $scores;
        parent::__construct();
    }

}
