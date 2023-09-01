<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Cdelivery;
use App\Models\Client;
use App\Models\Corder;
use App\Models\Doctor;
use App\Models\Icorder;
use App\Models\Location;
use App\Models\Material;
use App\Models\Pharmacy;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    //register doctor
    public function register(Request $request){
        $location_id = $request->input('location_id');
        $doctor_address = $request->input('doctor_address');
        $doctor_name = $request->input('doctor_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $doctor_phone = $request->input('doctor_phone');
        $doctor_description = $request->input('doctor_description');
        $doctor_image = $request->input('doctor_image');
        $d_image = $request->input('d_image');

        $check_doctor = Doctor::where('email',$email)->first();

        if(!$location_id || !$doctor_name || !$email || !$password || !$doctor_phone || !$doctor_description){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        if($check_doctor){
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

        $doctor = Doctor::create([
            'location_id'=>$location_id,
            'doctor_address'=>$doctor_address,
            'doctor_name'=>$doctor_name,
            'email'=>$email,
            'password'=>Hash::make($password),
            'doctor_phone'=>$doctor_phone,
            'doctor_description'=>$doctor_description,
            'doctor_image'=>$doctor_image,
            'd_image'=>$d_image,
        ]);

        $token = Auth::guard('doctor_api')->login($doctor);
        $state = $location->state;
        $location_name = $location->location_name;
        $state_name = $state->state_name;

        return response()->json([
            'message'=>'waiting for activate your account',
        ],200);
    }
    
    //login
    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');

        $doctor = Auth::guard('doctor_api')->attempt(['email'=>$email,'password'=>$password]);

        if(!$doctor){
            return response()->json([
                'error'=>'email or password is uncorrect',
            ]);
        }

        $doctor = Auth::guard('doctor_api')->user();
        $token = Auth::guard('doctor_api')->login($doctor);

        if($doctor->is_accept == 0){
            return response()->json([
                'message'=>'you can call us if your account not activated',
            ]);
        }

        return response()->json([
            'success'=>'login successfully',
            'doctor'=>$doctor,     
            'token'=>$token,
        ],200);
    }
    
    //logout
    public function logout(){
        Auth::guard('doctor_api')->logout();
        return response()->json([
            'message'=>'logout successfully',     
        ],200);
    }

    //dashboard
    public function dashboard(){
        return response()->json([
            'doctor'=>Auth::guard('doctor_api')->user(),     
        ],200);
    }

    //edit profile
    public function edit_doctor(Request $request){
    $doctor = Auth::guard('doctor_api')->user();

    $doctor_name = $request->input('doctor_name');
    $password = $request->input('password');
    $doctor_phone = $request->input('doctor_phone');
    $doctor_description = $request->input('doctor_description');
    $old_password = $request->input('old_password');

    if(!$doctor_name || !$password || !$doctor_phone || !$doctor_description || !$old_password){
        return response()->json([
            'error'=>'there are empty fields',     
        ]);
    }
    if(!Hash::check($old_password,$doctor->password)){
        return response()->json([
            'error'=>'uncorrect password',
        ]);
    }
    $doctor->update([
            'doctor_name'=>$doctor_name,
            'password'=>Hash::make($password),
            'doctor_phone'=>$doctor_phone,
            'doctor_description'=>$doctor_description,
        ]);
        return response()->json([
            'success'=>'updated successfully',
            'doctor'=>$doctor,
        ],200);
    }

    //create order
    public function create_order(Request $request, $pharmacy_id){
        $doctor = Auth::guard('doctor_api')->user();
        $pharmacy = Pharmacy::find($pharmacy_id);
        $corders = Corder::get();

        $co_description = $request->input('co_description');
        $address = $request->input('address');
        $is_delivery = $request->input('is_delivery');
        $client_id = $request->input('client_id');
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
            'doctor_id'=>$doctor->id,
            'client_id'=>$client_id,
            'corder_number'=>$corder_number,
            'date'=>$date,
            'co_description'=>$co_description,
            'is_delivery'=>$is_delivery,
            'cdelivery'=>$delivery_company,
            'address'=>$address,
        ]);

        return response()->json([
            'order'=>$order,   
        ],200);
    }

    //add item to order
    public function add_order_item(Request $request, $order_id){
        $doctor = Auth::guard('doctor_api')->user();
        $order = Corder::find($order_id);
        
        $material_id = $request->input('material_id');
        $quantity = $request->input('quantity');

        if(!$order || $order->doctor_id != $doctor->id){
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
        $doctor = Auth::guard('doctor_api')->user();
        $orders = $doctor->corder;

        return response()->json([
            'orders'=>$orders,     
        ],200);
    }

    //get order items
    public function get_order_items($order_id){
        $doctor = Auth::guard('doctor_api')->user();
        $order = Corder::find($order_id);

        if(!$order || $order->doctor_id != $doctor->id){
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

    //get all clients
    public function get_clients(){
        $clients = Client::get();

        return response()->json([
            'clients'=>$clients,
        ]);
    }

    //get client information
    public function get_client_information($client_id){
        $client = Client::find($client_id);

        return response()->json([
            'client'=>$client,
        ]);
    }

    //make report
    public function make_report(Request $request){
        $doctor = Auth::guard('doctor_api')->user();

        $from = $request->input('from');
        $to = $request->input('to');

        $corders = Corder::where(['doctor_id'=>$doctor->id,'is_accept'=>1])->where('date','>=',$from)->where('date','<=',$to)->get();

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
            'doctor_id'=>$doctor->id,
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