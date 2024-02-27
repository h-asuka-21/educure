<?php


namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

abstract class AbstractUserController extends Controller
{
    public function __construct()
    {
        // 認証ミドルウェア設定
        $this->middleware('user.auth');
    }

    public function getUser()
    {
        $user = Auth::user();
        if ($user === null) {
            throw new UnauthorizedException('Unauthorized', 401);
        }
        return $user;
    }

    protected function getCompanyId(): ?int
    {
        return $this->getUser()->company_id;
    }

    protected function getId(): ?int
    {
        return $this->getUser()->id;
    }

}
