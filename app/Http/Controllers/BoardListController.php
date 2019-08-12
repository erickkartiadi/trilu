<?php

namespace App\Http\Controllers;

use App\BoardList;
use App\Board;
use Illuminate\Http\Request;

class BoardListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('validate:board',['except'=>['index','destroy','right','left']]);
    }


    public function store(Request $request,Board $board)
    {
        $order = $board->lists->max('order') + 1;
        $newList = new BoardList;
        $newList->name = $request->name;
        $newList->order = $order;

        $board->lists()->save($newList);
        return response()->json(['message'=>'create list success'],200);
    }

    public function update(Request $request,Board $board ,BoardList $list)
    {
        $list->name = $request->name;
        $list->update();
        return response()->json(['message'=>'update list success']);
    }

    public function destroy(Board $board,BoardList $list)
    {
        $list->delete();
        return response()->json(['message'=>'delete list success'],200);
    }
    public function right(Request $request,Board $board,BoardList $list){
        $currentListOrder = $list->order;
        $lists = $board->lists()->orderBy('order','ASC')->get();
        $orders = array();
        foreach ($lists as $key=>$each){
            array_push($orders,$each->order);
            if($each->order === $currentListOrder) $currentKey = $key;
        }

        $max = $board->lists->max('order');
        if($currentListOrder === $max){
            return response()->json(['message'=>'you are the rightmost']);
        }

        $nextListOrder= $orders[$currentKey+1];
        $nextList = $board->lists->where('order',$nextListOrder)->first();
        $nextList->order = $currentListOrder;
        $list->order = $nextListOrder;
        $nextList->update();
        $list->update();
        return response()->json(['message'=>'move success']);
    }

    public function left(Request $request, Board $board, BoardList $list){
        $currentListOrder = $list->order;
        $orders = array();
        $lists = $board->lists()->orderBy('order','ASC')->get();
        foreach($lists as $key=>$each){
            array_push($orders,$each->order);
            if($currentListOrder === $each->order) $currentKey = $key;
        }

        $min = $board->lists->min('order');
        if($currentListOrder === $min) return reponse()->json(['message'=>'you are the leftmost'],200);
        $beforeListOrder = $orders[$currentKey-1];
        $beforeList = $board->lists->where('order',$orders[$currentKey-1])->first();
        $beforeList->order = $currentListOrder;
        $list->order = $beforeListOrder;
        $beforeList->update();
        $list->update();

        return response()->json(['message'=>'move success']);
    }
}
