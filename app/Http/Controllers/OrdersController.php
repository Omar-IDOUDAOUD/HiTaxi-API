<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    protected function setOrder(Request $req){
        $req->validate([
            'flight_id'=>'required|integer'
        ]); 
        AnalyticsController::makeOrderLog(Flight::find($req->flight_id)->from_place, Flight::find($req->flight_id)->to_place, auth()->user()->id, $req->flight_id); 
        try {
            if(User::find(Flight::find($req->flight_id)->driver)->role != 'driver' || auth()->user()->role != 'passenger'):
                return response(['message'=>'fail']);
            endif;  
            Order::create(
                [
                    'from_passenger'=>auth()->user()->id, 
                    'to_driver'=>Flight::find($req->flight_id)->driver,  
                    'flight_id'=>$req->flight_id 
                ]
            );
            return response(['message'=>'success']);
        } catch (\Throwable $th) {
            return response(['message'=>'fail']);
        }
    }

    protected function setAcceptStatue($order_id , Request $req){
        if(auth()->user()->id != Order::find($order_id)->to_driver): 
            return response(['message'=>'fail']);
        endif; 
        try {
        //
        Order::where('id', $order_id)->update(['accepted'=>$req->new_statue]); 
        } catch (\Throwable $th) {
            return response(['message'=>'fail']);
        }
        return response(['message'=>'success']);
        
    }

    protected function getOrders(){
        return Order::where('to_driver', auth()->user()->id)->get(); 
    }
}
