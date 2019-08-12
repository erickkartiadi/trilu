<?php

namespace App\Http\Controllers;

use App\LoginToken;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('validate:auth',['except'=>['login','logout']]);
    }
    public function register(Request $request){

        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();
        return $this->login($request);
    }
    public function login(Request $request){
        if(Auth::attempt(['username'=>$request->username,'password'=>$request->password])){
            $user = Auth::user();
            $token = bcrypt($user->id);
            $newToken = new LoginToken(['token'=>$token]);
            $user->tokens()->save($newToken);
            return response()->json(['token'=>$token],200);
        }
        return response()->json(['message'=>'invalid login'],401);
    }
    public function logout(Request $request){
        LoginToken::where('token',$request->header('token'))->delete();
        return response()->json(['message'=>'logout success'],200);
    }
}
