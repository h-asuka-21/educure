<?php


namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{

    public function index(): JsonResponse
    {
        // サーバー構成の動作確認用
        return response()->json(['a', 'b']);
    }
}
