<?php


namespace App\Http\Controllers;


use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class AbstractAuthController extends Controller
{
    protected string $guard;

    protected string $cookie_key;

    protected string $middleware_name;

    public function __construct()
    {
        $this->middleware($this->middleware_name, ['except' => ['login', 'refresh']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @param CookieJar $cookie
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request, CookieJar $cookie)
    {
        $credentials = request(['email', 'password', 'company_code']);
        $token = Auth::guard($this->guard)->attempt($credentials);
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token, $request, $cookie);
    }


    /**
     * Get the authentuser_tokenicated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = Auth::guard($this->guard)->user();
        if (empty($user)) {
            // ユーザーが取得できない
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $id = Auth::guard($this->guard)->user()->id;
        $user = $this->getUser($id);
        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @param Request $request
     * @param CookieJar $cookie
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request, CookieJar $cookie)
    {
        auth($this->guard)->logout();
        return response()->json(['message' => 'Successfully logged out'])->withCookie(
            $cookie->forget($this->cookie_key)
        );
    }

    /**
     * Refresh a token.
     *
     * @param Request $request
     * @param CookieJar $cookie
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request, CookieJar $cookie)
    {
        return $this->respondWithToken(auth($this->guard)->refresh(), $request, $cookie);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @param Request $request
     * @param CookieJar $cookie
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, Request $request, CookieJar $cookie)
    {
        return response()->json($this->getUser(auth($this->guard)->user()->id))->withCookie(
            $cookie->make(
                $this->cookie_key,
                $token,
                // サーバー側でトークンの期限を指定したいのでcookieの期限は長めに設ける。
                auth($this->guard)->factory()->getTTL() + 30,
                null,
                null,
                $request->getScheme() === 'https', // secure
                true
            ));
    }

    abstract protected function getUser(int $id): array;
}
