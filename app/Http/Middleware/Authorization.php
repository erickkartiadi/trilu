<?php

namespace App\Http\Middleware;

use App\LoginToken;
use Closure;

class Authorization
{
    public $attributes;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('token');
        $check = LoginToken::where('token',$token)->first();
        if(!$check){
            return response()->json(['message'=>'unauthorized user'],401);
        }
        $request->attributes->add(['user_id'=>$check->user->id]);
        return $next($request);
    }
}
