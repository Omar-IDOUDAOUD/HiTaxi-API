<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderLog;
use App\Models\SearchLog;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Float_;
use Ramsey\Uuid\Type\Integer;

class AnalyticsController extends Controller
{
    static public function makeSearchLog($from_place, $to_place, $by_user, $resultes_number){
        echo 'sdfs'; 
        SearchLog::create([
            'from_place'=>$from_place, 
            'to_place'=>$to_place, 
            'by_user'=>$by_user, 
            'resultes_number'=>$resultes_number
        ]);
    }

    static public function makeOrderLog($from_place, $to_place, $by_user, $flight_id){
        OrderLog::create([
            'from_place'=>$from_place, 
            'to_place'=>$to_place, 
            'by_user'=>$by_user, 
            'flight_id'=>$flight_id
        ]);
    }

    protected function getData(Request $req){
        $cell1 = SearchLog::where('from_place', $req->from_place)
        ->where('to_place', $req->to_place)
        ->where('created_at', '>=', date('Y-m-d H:i:s', time()-60*60*24*7))
        ->get('resultes_number'); 
        
        $res_numbers_total = 0; 
        foreach ($cell1 as $value) {
            $res_numbers_total += $value['resultes_number']; 
        }
        try {
            
        $resultes_average = (int)($res_numbers_total/count($cell1)); 
        } catch (\Throwable $th) {
            $resultes_average = 0; 
        }

        $cell2 = OrderLog::where('from_place', $req->from_place)
        ->where('to_place', $req->to_place)
        ->where('created_at', '>=', date('Y-m-d H:i:s', time()-60*60*24*7))
        ->join('hitaxi-db.flights', 'orderlogs.flight_id', '=', 'hitaxi-db.flights.id')
        ->select('orderlogs.*, hitaxi-db.flights.price ')->count('hitaxi-db.flights.price '); 
        
        $prices_total = $cell2->price; 

        echo $prices_total; 
        

        return response([
            'researchers_number'=>count($cell1), 
            'resultes_average'=>$resultes_average, 
            'demanders' => count($cell2)
        ]); 
    }
}
