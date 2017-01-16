<?php

namespace App\Http\Middleware;

use Closure;

use Tymon\JWTAuth\Facades\JWTAuth;

use Exception;

class authJWT

{

    public function handle($request, Closure $next)

    {

        try {

            $user = JWTAuth::toUser($request->input('token'));

        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>'fail','error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>'fail','error'=>'Token is Expired']);

            }else{

                return response()->json(['result'=>'fail','error'=>'Something is , check the passphrase']);

            }

        }

        return $next($request);

    }

}