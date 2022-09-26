<?php


namespace App\Http\Controllers;

use App\Http\Resources\FlightsResources;
use App\Http\Resources\SpecificFlightResources;
use App\Models\Flight;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SebastianBergmann\CodeCoverage\Driver\Selector;

class FlightsController extends Controller
{
    protected function read(Request $req)
    {

        $pack = Flight::join('users', 'flights.driver', '=', 'users.id')->select('flights.id', 'users.full_name as driver_name', 'flights.from_place', 'flights.to_place', 'flights.departure_time', 'flights.price', 'flights.cart', 'flights.free_places_left');

        $pack = isset($req['driver_name']) ? $pack->where('users.full_name', 'like', '%' . $req['driver_name'] . '%') : $pack;
        $pack = isset($req['from_place']) ? $pack->where('from_place', 'like', '%' . $req['from_place'] . '%') : $pack;
        $pack = isset($req['to_place']) ? $pack->where('to_place', 'like', '%' . $req['to_place'] . '%') : $pack;
        $pack = isset($req['departure_time']) ? $pack->where('departure_time', 'like', $req['departure_time'] . '%') : $pack;
        $pack = $pack->whereBetween('price', [$req['min_price'] ?? 0, $req['max_price'] ?? 1000000]);
        $pack = isset($req['cart']) ? $pack->where('cart', '=', $req['cart']) : $pack;
        $pack = isset($req['free_places_left']) ? $pack->where('free_places_left', '>=', $req['free_places_left']) : $pack;

        $pack = $pack->orderBy($req['order_by'] ?? 'id', in_array($req['order_mode'], ['desc', 'asc']) ? $req['order_mode'] : 'ASC')->Paginate($req['paginate'] ?? 1);

        if(isset($req['from_place'])||isset($req['to_place']) ): 
         AnalyticsController::makeSearchLog($req['from_place'],$req['to_place'], auth()->user()->id , count($pack));endif; 

        return response([
            "packs_lenght" => count($pack),
            "packs" => $pack->all()
        ]);
    }


    protected function readItem($id)
    { 

        /// see that: 
        return response(Flight::join('users', 'flights.driver', '=', 'users.id')->where('flights.id', '=', $id)
        ->select('flights.*', 'users.full_name as driver')
        ->get()->first() ?? null); 
    }



    protected function create(Request $req)
    {
        if( auth()->user()->role != 'driver'): 
            return response(['message' => 'fail']);
        endif; 

        $valid = $req->validate([
            'from_place' => 'required|string',
            'to_place' => 'required|string',
            'departure_time' => 'required|date',
            'maximum_passengers' => 'required|integer',
            'price' => 'nullable|integer',
            'cart' => ' string|nullable',
            'cart_mark' => ' string|nullable',
            'cart_image' => ' image|nullable|max:5999',
            'back_box_volume' => 'integer|nullable',
            'free_places_left' => 'integer|nullable'
        ]);


        $cartimagefile = $req->file('cart_image');

        $valid['cart_image'] = $cartimagefile ?  MediaController::generateFileName($cartimagefile) : null;
        $valid['driver'] = auth()->user()->id;

        try {
            $flight=Flight::create($valid);
            if ($req->hasFile('cart_image')) :
                MediaController::storeFile($cartimagefile, $valid['cart_image'], STORAGE_FLIGHTS_CARTS_IMAGES_PATH);
            endif;
            return response(['message' => 'success', 'flight_id'=>$flight->get('id')->last()->id]);
        } catch (\Throwable $th) {
            return response(['message' => 'fail']);
        }
    }


    protected function update($id, Request $req)
    {
        $valid = $req->validate([
            'departure_time' => 'required|date',
            'maximum_passengers' => 'required|integer',
            'price' => 'required|integer',
            'cart' => ' string|nullable',
            'cart_mark' => ' string|nullable',
            'cart_image' => ' image|nullable|max:5999',
            'back_box_volume' => 'integer|nullable',
            'free_places_left' => 'integer|nullable'
        ]);

        /// checking user: 
        if (auth()->user()->id == Flight::find($id)->driver) :

            $cartimagefile = $req->file('cart_image');

            $valid['cart_image'] = $cartimagefile ?  MediaController::generateFileName($cartimagefile) : null;

            Storage::delete(STORAGE_FLIGHTS_CARTS_IMAGES_PATH . '/' . Flight::find($id)->cart_image);

            Flight::where('id', $id)
                ->update($valid);

            if ($req->hasFile('cart_image')) :
                MediaController::storeFile($cartimagefile, $valid['cart_image'], STORAGE_FLIGHTS_CARTS_IMAGES_PATH);
            endif;

            return response(['message' => 'success']);
        else :
            return response(['message' => 'fail']);
        endif;
    }

    protected function delete($id)
    {
        $flight = Flight::find($id); 
        if ($flight && auth()->user()->id == $flight->driver) :
            $flight->delete(); 
                Storage::delete(STORAGE_FLIGHTS_CARTS_IMAGES_PATH . '/' .$flight->cart_image); 
            return response(['message' => 'success']);
        else :
            return response(['message' => 'fail']);
        endif;
    }

    protected function postedFilghts(Request $req)
    {
        $pack = Flight::where('driver', auth()->user()->id)
        ->select(['id', 'created_at', 'from_place', 'to_place', 'departure_time', 'price'])
        ->orderBy($req['order_by'] ?? 'id', in_array($req['order_mode'], ['desc', 'asc']) ? $req['order_mode'] : 'ASC')
        ->Paginate($req['paginate']); 
        return response([
            'packs_lenght'=> count($pack->all()), 
            'packs' => $pack->all()
        ]);
    }
}