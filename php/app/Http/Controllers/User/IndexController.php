<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\AbstractUserController;
use Illuminate\Http\JsonResponse;

class IndexController extends AbstractUserController
{
    public function index(): JsonResponse
    {
        // サーバー構成の動作確認用
        return response()->json(['user']);
    }

}
