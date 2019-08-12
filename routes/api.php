<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix'=>'v1'],function(){
    Route::post('auth/register','AuthController@register');
    Route::post('auth/login','AuthController@login');
    Route::group(['middleware'=>'authorization'],function(){
        Route::get('auth/logout','AuthController@logout');
        Route::resource('board','BoardController',['only'=>['index','store','update','destroy']]);
        Route::get('board/{board}','BoardController@open');
        Route::post('board/{board}/member','BoardController@addMember');
        Route::post('board/{board}/member/{user}','BoardController@removeMember');

        Route::post('board/{board}/list/{list}/right','BoardListController@right');
        Route::post('board/{board}/list/{list}/left','BoardListController@left');
        Route::resource('board.list','BoardListController',['only'=>['store','update','destroy']]);

        Route::post('card/{card}/up','CardController@up');
        Route::post('card/{card}/down','CardController@down');
        Route::post('card/{card}/move/{list}','CardController@move');
        Route::resource('board.list.card','CardController',['only'=>['store','update','destroy']]);


    });
});