<?php


namespace App\Http\Controllers;


abstract class AbstractAdminController extends Controller
{
    public function __construct()
    {
        // 認証ミドルウェア設定
        $this->middleware('admin.auth');
    }

}
