<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->sendErrorToken('Token is Invalid');
            } else if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException) {
                try {
                    if ($request->is('api/v1/auth/refresh')) {
                        return $next($request);
                    } else {
                        $user = JWTAuth::parseToken()->authenticate();
                    }
                } catch (Exception $e) {
                    return $this->unauthorized('Token is Expired', true);
                }
            } else {
                return $this->sendErrorToken('Authorization Token not found');
            }
        }

        if (!$roles) {
            return $next($request);
        }

        if ($user && in_array($user->role->name, $roles)) {
            return $next($request);
        }

        return $this->unauthorized("You don't have permission to this route");
    }

    protected function unauthorized($message, $refresh = false)
    {
        return response()->json([
            'success' => false,
            'refresh' => $refresh,
            'message' => $message
        ], Response::HTTP_UNAUTHORIZED);
    }

    protected function sendErrorToken($message)
    {
        return response()->json([
            'success' => false,
            'refresh' => false,
            'message' => $message
        ], Response::HTTP_BAD_REQUEST);
    }
}
