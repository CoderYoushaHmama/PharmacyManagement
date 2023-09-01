<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientLoginRequest;
use App\Http\Requests\ClientOrderItemRequest;
use App\Http\Requests\ClientOrderRequest;
use App\Http\Requests\ClientRegisterRequest;
use App\Models\Bill;
use App\Models\Cdelivery;
use App\Models\Client;
use App\Models\Corder;
use App\Models\Icorder;
use App\Models\Location;
use App\Models\Material;
use App\Models\Pharmacy;
use App\Models\Rate;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    //register client
    public function register(ClientRegisterRequest $request){
        $location_id = $request->input('location_id');
        $client_name = $request->input('client_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $client_phone = $request->input('client_phone');
        $client_description = $request->input('client_description');

        $check_client = Client::where('email',$email)->first();

        if(!$location_id || !$client_name || !$email || !$password || !$client_phone || !$client_description){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        if($check_client){
            return response()->json([
                'error'=>'this email alreaady used',
            ]);
        }

        $location = Location::find($location_id);

        if(!$location){
            return response()->json([
                'error'=>'this location not exist',
            ]);
        }

        
        if($request->messages){
            return response()->json([
                'message'=>$request->messages,
            ]);
        }

        $client = Client::create([
            'location_id'=>$location_id,
            'client_name'=>$client_name,
            'email'=>$email,
            'password'=>Hash::make($password),
            'client_phone'=>$client_phone,
            'client_description'=>$client_description,
        ]);

        $token = Auth::guard('client_api')->login($client);
        $state = $location->state;
        $location_name = $location->location_name;
        $state_name = $state->state_name;

        return response()->json([
            'client'=>$client,
            'token'=>$token,
            'location'=>$location_name,
            'state'=>$state_name,
        ],200);
    }

    //login
    public function login(ClientLoginRequest $request){
        $email = $request->input('email');
        $password = $request->input('password');

        $client = Auth::guard('client_api')->attempt(['email'=>$email,'password'=>$password]);

        if(!$client){
            return response()->json([
                'error'=>'email or password is uncorrect',
            ]);
        }

        $client = Auth::guard('client_api')->user();
        $token = Auth::guard('client_api')->login($client);

        return response()->json([
            'success'=>'login successfully',
            'client'=>$client,     
            'token'=>$token,
        ],200);
    }

    //logout
    public function logout(){
        Auth::guard('client_api')->logout();
        return response()->json([
            'message'=>'logout successfully',     
        ],200);
    }

    //dashboard
    public function dashboard(){
        return response()->json([
            'client'=>Auth::guard('client_api')->user(),     
        ],200);
    }

    //create order
    public function create_order(ClientOrderRequest $request, $pharmacy_id){
        $client = Auth::guard('client_api')->user();
        $pharmacy = Pharmacy::find($pharmacy_id);
        $corders = Corder::get();

        $co_description = $request->input('co_description');
        $address = $request->input('address');
        $is_delivery = $request->input('is_delivery');
        $corder_number = $corders->count()+1;
        $date = date('Y-m-d');
        $delivery_company = $request->input('cdelivery');

        if(!$pharmacy){
            return response()->json([
                'error'=>'this pharmacy not exist',
            ]);
        }

        $order = Corder::create([
            'pharmacy_id'=>$pharmacy_id,
            'client_id'=>$client->id,
            'corder_number'=>$corder_number,
            'date'=>$date,
            'cdelivery'=>$delivery_company,
            'co_description'=>$co_description,
            'is_delivery'=>$is_delivery,
            'address'=>$address,
        ]);

        return response()->json([
            'order'=>$order,   
        ],200);
    }

    //add item to order
    public function add_order_item(ClientOrderItemRequest $request, $order_id){
        $client = Auth::guard('client_api')->user();
        $order = Corder::find($order_id);
        
        $material_id = $request->input('material_id');
        $quantity = $request->input('quantity');

        if(!$order || $order->client_id != $client->id){
            return response()->json([
                'error'=>'this order not exist',
            ]);
        }

        $material = Material::find($material_id);

        if(!$material || $material->is_active == 0){
            return response()->json([
                'message'=>'this material not exist',
            ]);
        }

        $total_price = $material->ps * $quantity;

        $item = Icorder::where(['corder_id'=>$order_id,'material_id'=>$material_id])->first();

        if(!$item){
            Icorder::create([
                'material_id'=>$material_id,
                'corder_id'=>$order_id,
                'quantity'=>$quantity,
                'total_price'=>$total_price,
            ]);
            $order->update([
                'coitem_number'=>$order->coitem_number+1,
            ]);
        }else{
            $item->update([
                'quantity'=>$item->quantity+$quantity,
                'total_price'=>$item->total_price+$total_price,
            ]);
        }

        $order->update([
            'corder_total'=>$total_price+$order->corder_total,
        ]);

        return response()->json([
            'success'=>'this item added successfully',
        ],200);
    }

    //get orders
    public function get_orders(){
        $client = Auth::guard('client_api')->user();
        $orders = $client->corder;

        return response()->json([
            'orders'=>$orders,     
        ],200);
    }

    //get order items
    public function get_order_items($order_id){
        $client = Auth::guard('client_api')->user();
        $order = Corder::find($order_id);

        if(!$order || $order->client_id != $client->id){
            return response()->json([
                'error'=>'this order not exist',
            ]);
        }

        $items = $order->icorder;
        $pharmacy = Pharmacy::find($order->pharmacy_id);
        $cdelivery = Cdelivery::find($order->cdelivery);
        return response()->json([
            'pharmacy'=>$pharmacy,
            'items'=>$items,   
            'delivery_company'=>$cdelivery,  
        ],200);
    }

    //get bills
    public function get_bills(){
        $client = Auth::guard('client_api')->user();
        $bills = $client->bill_pharmacy;

        return response()->json([
            'bills'=>$bills,     
        ]);
    }

    //get bill items
    public function get_bill_items($bill_id){
        $client = Auth::guard('client_api')->user();
        $bill = Bill::find($bill_id);

        if(!$bill || $bill->client_id == $client->id){
            return response()->json([
                'error'=>'this bill not exist',
            ]);
        }

        return response()->json([
            'items'=>$bill->item,  
        ],200);
    }

    //edit profile
    public function edit_client(Request $request){
        $client = Auth::guard('client_api')->user();

        $location_id = $request->input('location_id');
        $client_name = $request->input('client_name');
        $password = $request->input('password');
        $client_phone = $request->input('client_phone');
        $client_description = $request->input('client_description');
        $old_password = $request->input('old_password');

        if(!$location_id || !$client_name || !$password || !$client_phone || !$client_description || !$old_password){
            return response()->json([
                'error'=>'there are empty fields',     
            ]);

        }
        if(!Hash::check($old_password,$client->password)){
            return response()->json([
                'error'=>'uncorrect password',
            ]);
        }
        $client->update([
            'location_id'=>$location_id,
            'client_name'=>$client_name,
            'password'=>Hash::make($password),
            'client_phone'=>$client_phone,
            'client_description'=>$client_description,
        ]);
        return response()->json([
            'success'=>'updated successfully',
            'client'=>$client,
        ],200);
    }

    //pharmacy rating
    public function rating($pharmacy_id, Request $request){
        $client = Auth::guard('client_api')->user();
        $pharmacy = Pharmacy::find($pharmacy_id);
        $rates = Rate::get();

        $rating = $request->input('rate');

        foreach($rates as $rate){
            if($client->id == $rate->client_id && $pharmacy->id == $rate->pharmacy_id){
                $rate->update([
                    'rating'=>$rating,
                ]);

                return response()->json([
                    'success'=>'rating success',
                    'rating'=>$rating,
                ],200);
            }
        }

        $rate = Rate::create([
            'client_id'=>$client->id,
            'pharmacy_id'=>$pharmacy_id,
            'rating'=>$rating,
        ]);

        return response()->json([
            'success'=>'rating success',
            'rating'=>$rating,
        ],200);
    }

    //make report
    public function make_report($type, Request $request){
        $client = Auth::guard('client_api')->user();

        $from = $request->input('from');
        $to = $request->input('to');
        
        $corders = Corder::where(['client_id'=>$client->id,'is_accept'=>1])->where('date','>=',$from)->where('date','<=',$to)->get();
        if($corders == '[]'){
            return response()->json([
                'empty'=>'no corders recently'
            ]);
        }
        $total = 0;
        foreach($corders as $corder){
            $total = $corder->corder_total;
        }
        
        $report = Report::create([
            'is_corder'=>1,
            'client_id'=>$client->id,
            'from'=>$from,
            'to'=>$to,
            'total'=>$total,
            'date'=>date('Y-m-d'),
        ]);
        return response()->json([
            'report'=>$report,
        ]);
    }
}