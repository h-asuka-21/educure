<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Token;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Models\User;

abstract class AbstractAuthenticate extends Middleware
{

    protected string $cookie_key;
    protected string $guard;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param array $guards
     * //     * @return mixed
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        try {
//            if(!$request->headers->has('x-xsrf-token')) throw new TokenMismatchException();

            $rawToken = $request->cookie($this->cookie_key);
            $token = new Token($rawToken);
            $payload = JWTAuth::decode($token);
            $user = $this->getUser($payload['sub']);
            Auth::guard($this->guard)->login($user);
        } catch (\Exception $e) {
            if ($e instanceof TokenExpiredException) {
                // リフレッシュ時
                try {
                    $newToken = JWTAuth::setToken($token)->refresh();
                    return $next($request)->withCookie(cookie(
                        $this->cookie_key,
                        $newToken,
                        config('jwt.refresh_ttl'),
                        null,
                        null,
                        $request->getScheme() === 'https',
                        true
                    ));
                } catch (\Exception $e) {
                    return response()->json(['error' => 'トークン再発行エラー'], 401);
                }
            }
            return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace(), 'guard' => $this->guard], 401);
        }
        return $next($request);
    }

    abstract protected function getUser($id);

}
