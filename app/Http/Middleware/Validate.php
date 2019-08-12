<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;

class Validate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$type)
    {
        switch ($type){
            case 'auth':
                $rules = [
                   'first_name' => 'alpha|between:2,20',
                   'last_name' => 'alpha|between:2,20',
                   'username' => array('regex:/^[\w|.]{5,12}$/','unique:users'),
                   'password' => 'between:5,12',
                ];
                break;
            case 'board':
                $rules = [
                    'name' => 'required'
                ];
                break;
            case 'card':
                $rules = [
                    'task' => 'required',
                ];
                break;
        }
        $validate = Validator::make($request->all(),$rules);
        if($validate->fails()){
            return response()->json(['message'=>$validate->errors()],422);
        }
        return $next($request);
    }
}
