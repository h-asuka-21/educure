<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AbstractAdminController;
use Illuminate\Http\JsonResponse;

class IndexController extends AbstractAdminController
{
    public function index(): JsonResponse
    {
        // サーバー構成の動作確認用
        return response()->json(['admin']);
    }

}
