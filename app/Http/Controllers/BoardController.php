<?php

namespace App\Http\Controllers;

use App\Board;
use App\BoardMember;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $users =  DB::select('SELECT * from BOARD_MEMBERS
            INNER JOIN BOARDS ON BOARD_MEMBERS.board_id = BOARDS.id 
            INNER JOIN USERS ON BOARD_MEMBERS.user_id = USERS.id 
            WHERE board_id = ? AND boards.creator_id != board_members.user_id
        ',[$board->id]);

        $board_result = Board::where('id',$board->id)->with('lists.cards')->get();
        $board_result[0]->users = array_map(function($user) {
            $user->initial = substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1);
            return $user;
        }, $users);
        
        return $board_result;
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
