<?php


namespace App\Http\Controllers;


use App\Models\Student\Student;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

abstract class AbstractStudentController extends Controller
{
    public function __construct()
    {
        // 認証ミドルウェア設定
        $this->middleware('student.auth');
    }

    /**
     * @return Student|\Illuminate\Contracts\Auth\Authenticatable
     */
    private function getUser()
    {
        $user = Auth::guard('student')->user();
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

    protected function getCourseId(): ?int
    {
        return $this->getUser()->course_id;
    }

    protected function getEmail(): ?string
    {
        return $this->getUser()->email;
    }

    protected function reHashToken(Request $request, CookieJar $cookie): \Symfony\Component\HttpFoundation\Cookie
    {
        $user = Auth::guard('student');
        if (!$user) {
            throw new UnauthorizedException('Unauthorized', 401);
        }
        $token = $user->refresh();
        return $cookie->make(
            'student_token',
            $token,
            // サーバー側でトークンの期限を指定したいのでcookieの期限は長めに設ける。
            config('jwt.refresh_ttl') + 30,
            null,
            null,
            $request->getScheme() === 'https', // secure
            true
        );
    }
}
