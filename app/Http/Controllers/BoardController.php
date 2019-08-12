<?php

namespace App\Http\Controllers;

use App\Board;
use App\BoardMember;
use App\User;
use Illuminate\Http\Request;

class BoardController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('validate:board',['except'=>['destroy','index','open','addMember','removeMember']]);
    }

    public function index(Request $request)
    {
        $current_user_id = $request->get('user_id');
        $user = User::find($current_user_id);
        return $user->manyBoards;
    }
    public function store(Request $request)
    {
        $newBoard = new Board(['name'=>$request->name]);

        $user = User::find($request->get('user_id'));
        $user->boards()->save($newBoard);
        $user->manyBoards()->attach($newBoard);
        return response()->json(['message'=>'create board success'],200);
    }
    public function update(Request $request, Board $board)
    {
        $board->name = $request->name;
        $board->update();
        return response()->json(['message'=>'update board success'],200);
    }

    public function destroy(Board $board)
    {
        $board->delete();
        return response()->json(['message'=>'delete board success'],200);
    }

    public function open(Board $board){
        return Board::where('id',$board->id)->with('lists.cards')->get();
    }

    public function addMember(Request $request,Board $board){
            $newMember = User::where('username',$request->username)->firstOrFail();
            $boardMember = new BoardMember;
            $boardMember->board_id = $board->id;
            $boardMember->user_id = $newMember->id;
            $boardMember->save();
    }

    public function removeMember(Board $board,User $user){
        BoardMember::where('board_id',$board->id)->where('user_id',$user->id)->delete();
        return response()->json(['message'=>'delete member success'],200);
    }
}
