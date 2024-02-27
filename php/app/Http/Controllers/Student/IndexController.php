<?php


namespace App\Http\Controllers\Student;


use App\Http\Controllers\AbstractStudentController;
use Illuminate\Http\JsonResponse;

class IndexController extends AbstractStudentController
{
    public function index(): JsonResponse
    {
        // サーバー構成の動作確認用
        return response()->json(['student']);
    }

}
