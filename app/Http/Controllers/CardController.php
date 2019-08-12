<?php

namespace App\Http\Controllers;


use App\Board;
use App\BoardList;
use App\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{

    public function __construct()
    {
        $this->middleware('validate:card',['except'=>['index','destroy','up','down','move']]);
    }


    public function store(Request $request, Board $board, BoardList $list)
    {
        $newCard = new Card;
        $newCard->task = $request->task;
        $newCard->order = $list->cards->max('order') + 1;
        $list->cards()->save($newCard);
        return response()->json(['message'=>'create card success'],200);
    }

    public function update(Request $request,Board $board, BoardList $list, Card $card)
    {
        $card->task = $request->task;
        $card->save();
        return response()->json(['message'=>'update card success'],200);
    }

    public function destroy(Board $board, BoardList $list,Card $card)
    {
        $card->delete();
        return response()->json(['message'=>'delete card success'],200);
    }
    public function up(Card $card){
        $currentCardOrder = $card->order;

        $cards = $card->list->cards()->orderBy('order','ASC')->get();
        $orders = array();
        foreach($cards as $key=>$each){
            array_push($orders, $each->order);
            if($each->order === $currentCardOrder) $currentKey = $key;
        }

        $min = $card->list->cards->min('order');
        if($currentCardOrder === $min){
            return response()->json(['message'=>'you are the top one'],200);
        }

        $aboveCard = $card->list->cards->where('order',$orders[$currentKey-1])->first();
        $aboveCardOrder = $aboveCard->order;

        $card->order = $aboveCardOrder;
        $aboveCard->order = $currentCardOrder;
        $card->update();
        $aboveCard->update();
        return response()->json(['message'=>'move success'],200);
    }

    public function down(Card $card){
        $currentCardOrder = $card->order;
        $cards = $card->list->cards()->orderBy('order','ASC')->get();
        $orders = array();
        foreach($cards as $key=>$each){
            array_push($orders,$each->order);
            if($each->order === $currentCardOrder) $currentKey = $key;
        }
        $max = $card->list->cards->max('order');
        if($max === $currentCardOrder) return response()->json(['message'=>'your are the bottom one'],200);
        $belowCard = $card->list->cards->where('order',$orders[$currentKey+1])->first();
        $belowCardOrder = $belowCard->order;

        $card->order = $belowCardOrder;
        $belowCard->order = $currentCardOrder;
        $card->update();
        $belowCard->update();

        return response()->json(['message'=>'move success'],200);
    }

    public function move(Card $card,BoardList $list){
        $destinationListId = $list->id;

        $max = $list->cards->max('order') + 1;

        $card->list_id = $destinationListId;
        $card->order = $max;
        $card->update();

        return response()->json(['message'=>'move success'],200);
    }
}
