<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\BillItemRequest;
use App\Http\Requests\BillRequest;
use App\Http\Requests\ContentEditRequest;
use App\Http\Requests\ContentRequest;
use App\Http\Requests\DetailsRequest;
use App\Http\Requests\EmployeeRegisterRequest;
use App\Http\Requests\OrderItemRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\PharmacyEditRequest;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\PharmacyLoginRequest;
use App\Http\Requests\PharmacyRegisterRequest;
use App\Http\Requests\StockItemRequest;
use App\Http\Requests\StockRequest;
use App\Http\Requests\WasteRequest;
use App\Http\Requests\WasteItemRequest;
use App\Models\Batch;
use App\Models\Message;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Cdelivery;
use App\Models\Client;
use App\Models\Company;
use App\Models\Content;
use App\Models\Corder;
use App\Models\Detail;
use App\Models\Doctor;
use App\Models\Employee;
use App\Models\Favorite;
use App\Models\Istock;
use App\Models\Location;
use App\Models\Material;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pharmacy;
use App\Models\RequireModel;
use App\Models\Report;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Waste;
use App\Models\WasteItem;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Symfony\Contracts\Service\Attribute\Required;

class PharmacyController extends Controller
{
    //create pharmacy
    public function register(PharmacyRegisterRequest $request){
        $location_id = $request->input('location_id');
        $pharmacy_address = $request->input('pharmacy_address');
        $pharmacy_name = $request->input('pharmacy_name');
        $pharmacy_email = $request->input('pharmacy_email');
        $pharmacy_password = $request->input('pharmacy_password');
        $pharmacy_phone = $request->input('pharmacy_phone');  
        $line_phone = $request->input('line_phone');  
        $pharmacy_image = $request->input('pharmacy_image');
        $no_image = $request->input('no_image');
        $pharmacy_wallet = $request->input('pharmacy_wallet');
        $no_facility = $request->input('no_facility');
        $pharmacy_owner = $request->input('pharmacy_owner');

        $check_pharmacy = Pharmacy::where('email',$pharmacy_email)->first();

        if(!$location_id || !$pharmacy_address || !$pharmacy_name || !$pharmacy_email ||
        !$pharmacy_password || !$pharmacy_phone || !$line_phone || !$no_image || 
        !$pharmacy_wallet || !$no_facility || !$pharmacy_owner){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        if($check_pharmacy){
            return response()->json([
                'error'=>'this email already exist',
            ]);
        }

        $location = Location::find($location_id);
        if(!$location){
            return response()->json([
                'error'=>'this location not exist',
            ]);
        }

        $pharmacy = Pharmacy::create([
            'location_id'=> $location_id,
            'pharmacy_address'=> $pharmacy_address,
            'pharmacy_name'=>$pharmacy_name,
            'email'=>$pharmacy_email,
            'password'=>Hash::make($pharmacy_password),
            'pharmacy_phone'=>$pharmacy_phone,
            'line_phone'=>$line_phone,
            'pharmacy_wallet'=>$pharmacy_wallet,
            'no_facility'=>$no_facility,
            'no_image'=>$no_image,
            'pharmacy_owner'=>$pharmacy_owner,
        ]);

        if($pharmacy_image){
            $pharmacy->update([
                'pharmacy_image'=>$pharmacy_image,
            ]);
        }

        return response()->json([
            'message'=>'pharmacy created successfully and in wait',
        ],200);
    }

    //login pharmacy
    public function login(PharmacyLoginRequest $request){
        $pharmacy_email = $request->input('pharmacy_email');
        $pharmacy_password = $request->input('pharmacy_password');

        if(!$pharmacy_email || !$pharmacy_password){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        $pharmacy = Auth::guard('pharmacy_api')->attempt(['email'=>$pharmacy_email,'password'=>$pharmacy_password]);
        if(!$pharmacy){
            return response()->json([
                'error'=>'password or email not correct',
            ]);
        }

        $pharmacy = Auth::guard('pharmacy_api')->user();

        if($pharmacy->is_active == 0){
            return response()->json([
                'message'=>'if sure your account not active call us: 095446565',
            ]);
        }
        $token = Auth::guard('pharmacy_api')->login($pharmacy);

        $location = $pharmacy->location;
        $state = $location->state;
        $location_name = $location->location_name;
        $state_name = $state->state_name;

        return response()->json([
            'message'=>'login successfully',
            'pharmacy'=>$pharmacy,
            'token'=>$token,
            'state'=>$state_name,
            'location'=>$location_name,
        ],200);
    }

    //logout pharmacy
    public function logout(){
        Auth::guard('pharmacy_api')->logout();
        return response()->json([
            'message'=>'logout successfully',
        ]);
    }

    //pharmacy dashboard
    public function dashboardInvoked(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $location = $pharmacy->location;
        $state = $location->state;
        $location_name = $location->location_name;
        $state_name = $state->state_name;
        return [
            'pharmacy'=> $pharmacy,
            'state'=> $state_name,
            'location'=> $location_name,
        ];
    }

    //get pharmacy employees
    public function get_employees(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        return response()->json([
            'employees'=> $pharmacy->employee,
        ]);
    }

    //get employee information
    public function get_employee_information($employee_id){
        $employee = Employee::find($employee_id);

        return response()->json([
            'employee'=> $employee,
        ]);
    }

    //add employee to pharmacy
    public function add_employee(EmployeeRegisterRequest $request){
        $employee_name = $request->input('employee_name');
        $employee_email = $request->input('employee_email');
        $employee_password = $request->input('employee_password');
        $employee_address = $request->input('employee_address');
        $employee_phone = $request->input('employee_phone');
        $salary = $request->input('salary');

        if(!$employee_name || !$employee_email || !$employee_password || !$employee_address ||
        !$employee_phone || !$salary){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        $check_employee = Employee::where('email',$employee_email)->first();

        if($check_employee){
            return response()->json([
                'error'=>'this email already exist',
            ]);
        }
        
        $pharmacy = Auth::guard('pharmacy_api')->user();

        $employee = Employee::create([
            'message'=>'employee added successfully',
            'pharmacy_id'=>$pharmacy->id,
            'employee_name'=>$employee_name,
            'email'=>$employee_email,
            'password'=>Hash::make($employee_password),
            'employee_address'=>$employee_address,
            'employee_phone'=>$employee_phone,
            'salary'=>$salary,
        ]);

        return response()->json([
            'employee'=>$employee,
        ]);
    }

    //set pharmacy details
    public function set_details(DetailsRequest $request){
        $day = $request->input('day');
        $open = $request->input('open');
        $close = $request->input('close');
        $is_duty = $request->input('is_duty');
        
        $pharmacy = Auth::guard('pharmacy_api')->user();;
        if(!$pharmacy){
            return response()->json([
                'error'=>'this pharmacy not exist',
            ]);
        }

        if(!$day || !$open || !$close || !$is_duty){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        $check_detail = Detail::where(['pharmacy_id'=>$pharmacy->id,'day'=>$day])->first();

        if($check_detail){
            return response()->json([
                'error'=>'this day already used',
            ]);
        }

        $detail = Detail::create([
            'pharmacy_id'=>$pharmacy->id,
            'day'=>$day,
            'open'=>$open,
            'close'=>$close,
            'is_duty'=>$is_duty,
        ]);

        return response()->json([
            'message'=>'details added successfully',
            'detail'=>$detail,
        ]);
    }

    //get pharmacy details
    public function get_details(){
        $pharmacy = Auth::guard('pharmacy_api')->user();;
        $details = $pharmacy->detail;

        return response()->json([
            'details'=>$details,
        ],200);
    }

    //remove employee from pharmacy
    public function remove($employee_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $employee = Employee::find($employee_id);

        if(!$pharmacy){
            return response()->json([
                'error'=>'this pharmacy not exist',
            ]);
        }
        
        if(!$employee){
            return response()->json([
                'error'=>'this employee not exist',
            ]);
        }

        if($employee->pharmacy_id == $pharmacy->id){
            $employee->delete();
            return response()->json([
                'success'=>'this employee removed',
            ],200);
        }
        return response()->json([
            'error'=>'this employee not exist',
        ]);
    }

    //edit employee information
    public function edit_employee($employee_id,EmployeeRegisterRequest $request){
        $employee_name = $request->input('employee_name');
        $password = $request->input('employee_password');
        $employee_address = $request->input('employee_address');
        $employee_phone = $request->input('employee_phone');
        $salary = $request->input('salary');

        $pharmacy = Auth::guard('pharmacy_api')->user();
        $employee = Employee::find($employee_id);

        if(!$pharmacy){
            return response()->json([
                'error'=>'this pharmacy not exist',
            ]);
        }
        
        if(!$employee){
            return response()->json([
                'error'=>'this employee not exist',
            ]);
        }

        if($employee->pharmacy_id == $pharmacy->id){
            $employee->update([
                'employee_name'=>$employee_name,
                'password'=>Hash::make($password),
                'employee_address'=>$employee_address,
                'employee_phone'=>$employee_phone,
                'salary'=>$salary,
            ]);
            return response()->json([
                'success'=>'this employee updated successfully',
            ],200);
        }
        return response()->json([
            'error'=>'this employee not exxist',
        ]);
    }

    //give salary to employee
    public function give_salary($employee_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $employee = Employee::find($employee_id);

        if($employee->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this employee not exist',
            ]);
        }

        if($pharmacy->pharmacy_wallet >= $employee->salary){
        $employee->update([
            'wallet'=> $employee->wallet+$employee->salary,
        ]);

        $pharmacy->update([
            'pharmacy_wallet'=> $pharmacy->pharmacy_wallet-$employee->salary,
        ]);
        
        return response()->json([
            'success'=>'success',
        ],200);
    }
        return response()->json([
            'error'=>'not enoght money in pharmacy wallet',
        ],200);
    }

    //edit pharmacy profile
    public function edit_profile(PharmacyEditRequest $request){
        $pharmacy = Auth::guard('pharmacy_api')->user();

        $pharmacy_name = $request->input('pharmacy_name');
        $password = $request->input('pharmacy_password');
        $pharmacy_phone = $request->input('pharmacy_phone');
        $line_phone = $request->input('line_phone');
        $pharmacy_image = $request->input('pharmacy_image');
        $pharmacy_owner = $request->input('pharmacy_owner');
        $old_password = $request->input('pharmacy_old_password');

        if(!Hash::check($old_password,$pharmacy->password)){
            return response()->json([
                'error'=>'uncorrect password',
            ]);
        }

        $pharmacy->update([
            'pharmacy_name'=>$pharmacy_name,
            'password'=>Hash::make($password),
            'pharmacy_phone'=>$pharmacy_phone,
            'line_phone'=>$line_phone,
            'pharmacy_image'=>$pharmacy_image,
            'pharmacy_owner'=>$pharmacy_owner,
        ]);

        return response()->json([
            'success'=>'update successfully',
        ],200);
    }

    //edit pharmacy working hours
    public function edit_details(Request $request,$detail_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $detail = Detail::find($detail_id);

        $open = $request->input('open');
        $close = $request->input('close');
        $is_duty = $request->input('is_duty');

        if(!$open || !$close || !$is_duty){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        if(!$open || !$close){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        if($pharmacy->id != $detail->pharmacy_id){
            return response()->json([
                'error'=>'this detail not exist',
            ]);
        }

        $detail->update([
            'open'=>$open,
            'close'=>$close,
            'is_duty'=>$is_duty,
        ]);

        return response()->json([
            'success'=>'updated successfully',
        ],200);
    }

    //remove pharmacy detail
    public function remove_details($detail_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $detail = Detail::find($detail_id);

        if($pharmacy->id != $detail->pharmacy_id){
            return response()->json([
                'error'=>'this detail not exist',
            ]);
        }

        $detail->delete();
        
        return response()->json([
            'success'=>'deleted successfully',
        ],200);
    }

    //create order
    public function create_order(OrderRequest $request){
        $pharmacy = Auth::guard('pharmacy_api')->user();

        $company_id = $request->input('company_id');
        $order_number = $request->input('order_number');
        $order_description = $request->input('order_description');

        $company = Company::find($company_id);

        $date = date('Y-m-d');

        if(!$company){
            return response()->json([
                'error'=>'this company not exist',
            ]);
        }

        $order = Order::create([
            'pharmacy_id'=>$pharmacy->id,
            'company_id'=>$company_id,
            'date'=>$date,
            'order_number'=>$order_number,
            'order_description'=>$order_description,
        ],200);

        return response()->json([
            'success'=>'order created successfully',
            'order'=>$order,
        ]);
    }

    //add item to order
    public function add_order_item($order_id,OrderItemRequest $request){
        $order = Order::find($order_id);
        $pharmacy = Auth::guard('pharmacy_api')->user();

        $material_id = $request->input('material_id');
        $quantity = $request->input('quantity');
        $batch_id = $request->input('batch_id');

        $material = Material::find($material_id);
        $batch = Batch::find($batch_id);
        
        if(!$order || $order->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this order not exist',
            ]);
        }

        if(!$material || $material->company_id != $order->company_id){
            return response()->json([
                'error'=>'this material not exist',
            ]);
        }

        if(!$batch || $batch->material_id != $material->id || !$batch->is_active){
            return response()->json([
                'error'=>'this batch not exist',
            ]);
        }

        // if($quantity > $batch->quantity){
        //     return response()->json([
        //         'message'=>'not enought materials in this batch',  
        //     ]);
        // }

        $total_price = $material->pp * $quantity;
        $cost = $total_price+$order->order_total;

        // if($cost > $pharmacy->pharmacy_wallet){
        //     return response()->json([
        //         'message'=>'not enought money in wallet',
        //         'cost'=>$total_price,
        //     ]);
        // }

        //$item = OrderItem::where(['order_id'=>$order_id,'material_id'=>$material->id,'batch_id'=>$batch_id])->first();
        $item = OrderItem::where(['order_id'=>$order_id,'material_id'=>$material->id])->first();


        if(!$item){
            $order->update([
                'items_count'=>$order->items_count+1,
            ]);

            $item = OrderItem::create([
                'order_id'=>$order_id,
                'material_id'=>$material_id,
                'quantity'=>$quantity,
                'total_price'=>$total_price,
                'batch_id'=>$batch_id,
            ]);
        }else if($item->batch_id == $batch_id){
            $item->update([
                'quantity'=>$item->quantity + $quantity,
                'total_price'=>$item->$total_price + $total_price,
            ]);
        }else{
            $item = OrderItem::create([
                'order_id'=>$order_id,
                'material_id'=>$material_id,
                'quantity'=>$quantity,
                'total_price'=>$total_price,
                'batch_id'=>$batch_id,
            ]);
        }

        $order->update([
            'order_total'=>$total_price+$order->order_total,
        ]);

        return response()->json([
            'success'=>'item added to order successfully',
            'item'=>$item,
        ],200);
    }

    //send order
    public function send_order($order_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $order = Order::find($order_id);
        if($order->order_total > $pharmacy->pharmacy_wallet){
            return response()->json([
                'error'=>'not enought money in wallet',
            ]);
        }

        $order->update([
            'is_sent'=>1,
        ]);
        return response()->json([
            'success'=>'sent successfully'
        ],200);
    }

    //get pharmacy orders
    public function get_order(){
        $pharmacy = Auth::guard('pharmacy_api')->user();

        $orders = $pharmacy->order;

        return response()->json([
            'orders'=>$orders,
        ]);
    }

    //get order details
    public function get_order_details($order_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $order = Order::find($order_id);

        if(!$order || $order->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this order not exist',
            ]);
        }

        $items = $order->order_item;
        $materials = $order->material;

        return response()->json([
            'items'=>$items,
            'materials'=>$materials,
        ],200);
    }

    //get detaisl of batch items
    public function get_item_batch_details($order_id,$item_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $order = Order::find($order_id);
        $item = OrderItem::find($item_id);

        if(!$order || $order->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this order not exist',
            ]);
        }

        if(!$item || $item->order_id != $order->id){
            return response()->json([
                'error'=>'this item not exist',
            ]);
        }

        $batch = Batch::find($item->batch_id);

        if(!$batch || !$batch->is_active){
            return response()->json([
                'error'=>'this batch not exist',
            ]);
        }

        return response()->json([
            'batch'=>$batch,     
        ],200);
    }

    //add content to pharmacy
    public function add_content(ContentRequest $request){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        
        $material_id = $request->input('material_id');
        $batch_id = $request->input('batch_id');
        $quantity = $request->input('quantity');
        $min = $request->input('min');
        $max = $request->input('max');

        if(!$material_id || !$batch_id || !$quantity || !$min || !$max){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        $material = Material::find($material_id);
        $batch = Batch::find($batch_id);

        if(!$material){
            return response()->json([
                'error'=>'this material not exist',
            ]);
        }

        if(!$batch || $batch->material_id != $material_id){
            return response()->json([
                'error'=>'this batch not exists',
            ]);
        }

        $contents = $pharmacy->content;

        foreach($contents as $content){
            if($content->batch_id == $batch_id && $content->material_id == $material_id && $content->pharmacy_id == $pharmacy->id){
                $content->update([
                    'quantity'=>$content->quantity+$quantity,
                    'min'=>$min,
                    'max'=>$max,
                ]);
                return response()->json([
                    'success'=>'this content added successfully',
                ]);
            }
        }

        Content::create([
            'pharmacy_id'=>$pharmacy->id,
            'material_id'=>$material_id,
            'batch_id'=>$batch_id,
            'quantity'=>$quantity,
            'min'=>$min,
            'max'=>$max,
        ]);

        return response()->json([
            'success'=>'this content added successfully',
        ]);
    }

    //edit pharmacy content
    public function edit_content($content_id, Request $request){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $content = Content::find($content_id);

        if(!$content || $content->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this content not exist',
            ]);
        }

        $min = $request->input('min');
        $max = $request->input('max');

        if(!$min || !$max){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        if(!$min || !$max){
            return response()->json([
                'error'=>'there is empty field',     
            ]);
        }

        $content->update([
            'min'=>$min,
            'max'=>$max,
        ]);

        return response()->json([
            'success'=>'content updated successfully',     
        ],200);
    }

    //minimum notification
    public function minimum_notification(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $contents = Content::where('pharmacy_id',$pharmacy->id)->whereColumn('min', '>=' ,'quantity')->get();

        if($contents == '[]'){
            return response()->json([
                'message'=>'not minimum contents yet',
            ],200);
        }
        return response()->json([
            'contents'=>$contents,
        ],200);
    }

    //maximum notification
    public function maximum_notification(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $contents = Content::where('pharmacy_id',$pharmacy->id)->whereColumn('max', '<=' ,'quantity')->get();

        if($contents == '[]'){
            return response()->json([
                'message'=>'not maximum contents yet',
            ],200);
        }

        return response()->json([
            'contents'=>$contents,
        ],200);
    }

    //get require
    public function get_require($employee_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $employee = Employee::find($employee_id);

        if(!$employee || $employee->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this employee not exist',
            ]);
        }

        return response()->json([
            'requires'=>$employee->requires,
            'materials'=>$employee->material,
        ],200);
    }

    //reject require
    public function reject_require($require_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $require = RequireModel::find($require_id);
        $employee = Employee::find($require->employee_id);

        if(!$employee || $employee->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this employee not exist',
            ]);
        }

        if(!$require || $require->employee_id != $employee->id){
            return response()->json([
                'error'=>'this require not exist',
            ]);
        }

        if($require->is_accept == 1){
            return response()->json([
                'message'=>'this require already accepted',
            ]);
        }

        $require->update([
            'is_accept'=>0,
        ]);

        return response()->json([
            'success'=>'rejected sucessfully',
        ],200);
    }

    //accept require
    public function accept_require($require_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        // $employee = Employee::find($employee_id);
        $require = RequireModel::find($require_id);

        // if(!$require || $require->employee_id != $employee_id){
        //     return response()->json([
        //         'error'=>'this require not exist',
        //     ]);
        // }

        $require->update([
            'is_accept'=>1,
        ]);

        return response()->json([
            'success'=>'accepted sucessfully',
        ],200);
    }

    //create bill
    public function create_bill(BillRequest $request){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        
        $client_id = $request->input('client_id');
        $client_name = $request->input('client_name');
        $date = date('Y-m-d');
        $bill_number = $request->input('bill_number');
        $is_delivery = $request->input('is_delivery');
        $delivery_cost = $request->input('delivery_cost');
        $delivery_company = $request->input('delivery_company');
        $is_return = $request->input('is_return');

        $client = Client::find($client_id);
        if(!$client && !$client_name){
            return response()->json([
                'error'=>'this client not exist',
            ]);
        }
    
        $bill = Bill::create([
            'pharmacy_id'=>$pharmacy->id,
            'client_id'=>$client_id,
            'client_name'=>$client_name,
            'date'=>$date,
            'bill_number'=>$bill_number,
            'is_delivery'=>$is_delivery,
            'delivery_cost'=>$delivery_cost,
            'cdelivery'=>$delivery_company,
            'is_return'=>$is_return,
        ]);
    
        if($bill->is_delivery){
            $bill->update([
                'bill_total'=>$bill->bill_total+$delivery_cost,
            ]);
        }

        return response()->json([
            'success'=>'bill created successfully',
            'bill'=>$bill,
        ]);
    }

    //add item to bill
    public function add_bill_item($bill_id, BillItemRequest $request){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $bill = Bill::find($bill_id);

        $content_id = $request->input('content_id');
        $quantity = $request->input('quantity');

        $content = Content::find($content_id);

        if(!$bill || $bill->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this order not exist',
            ]);
        }

        if(!$content || $content->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this content not exist',
            ]);
        }

        if($quantity > $content->quantity){
            return response()->json([
                'message'=>'not enought materials',
            ]);
        }

        $material = Material::find($content->material_id);
        $item = BillItem::where(['bill_id'=>$bill_id,'content_id'=>$content_id])->first();

        $content->update([
            'quantity'=>$content->quantity-$quantity,
        ]);

        $total_price = $material->ps * $quantity;

        $items = $bill->item;

        if($items == '[]'){
            $item = BillItem::create([
                'bill_id'=>$bill_id,
                'content_id'=>$content_id,
                'quantity'=>$quantity,
                'b_total'=>$total_price,
            ]);

            $bill->update([
                'bitem_number'=>$bill->bitem_number+1,
            ]);


            $bill->update([
                'bill_total'=>$bill->bill_total+$total_price,
            ]);


            $pharmacy->update([
                'pharmacy_wallet'=>$pharmacy->pharmacy_wallet+$total_price,
            ]);

            return response()->json([
                'success'=>'added successfully',
                'msg'=>'at first',
            ],200);
        }

        $i=0;
        for($i=0;$i<$items->count();$i++){
            $check_content = Content::find($items[$i]->content_id);

            if($check_content->material_id == $content->material_id){
                $items[$i]->update([
                    'quantity'=>$items[$i]->quantity+$quantity,
                    'b_total'=>$items[$i]->b_total+$total_price,
                ]);

                $bill->update([
                    'bill_total'=>$bill->bill_total+$total_price,
                ]);
            
            
                $pharmacy->update([
                    'pharmacy_wallet'=>$pharmacy->pharmacy_wallet+$total_price,
                ]);
            
                return response()->json([
                    'success'=>'added successfully',
                ],200);
            }
        }

        if($i == $items->count()){
            $bill->update([
                    'bitem_number'=>$bill->bitem_number+1,
            ]);

            BillItem::create([
                'bill_id'=>$bill_id,
                'content_id'=>$content_id,
                'quantity'=>$quantity,
                'b_total'=>$total_price,
            ]);
            $bill->update([
                'bill_total'=>$bill->bill_total+$total_price,
            ]);
            $pharmacy->update([
                'pharmacy_wallet'=>$pharmacy->pharmacy_wallet+$total_price,
            ]);
            return response()->json([
                'success'=>'added successfully',
            ],200);
        }
    }

    //get pharmacy bills
    public function get_bills(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $bills = $pharmacy->bill;

        return response()->json([
            'bills'=>$bills,
        ],200);
    }

    //get bill items
    public function get_bill_items($bill_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $bill = Bill::find($bill_id);

        if(!$bill || $bill->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this bill not exist',
            ]);
        }

        $items = $bill->item;
        $cdelivery = Cdelivery::find($bill->cdelivery);
        return response()->json([
            'items'=>$items,
            'contents'=>$bill->content,
            'delevery_company'=>$cdelivery,
        ],200);
    }

    //get bill content
    public function get_bill_content($bill_id,$item_id){
        $item = BillItem::find($item_id);
        $content = Content::find($item->content_id);
        $material = Material::find($content->material_id);

        return response()->json([
            'content'=>$content,
            'material'=>$material,
        ]);
    }

    //return bill
    public function return_bill($bill_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $bill = Bill::find($bill_id);

        if(!$bill || $bill->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this bill not exist',
            ]);
        }

        if($bill->is_return){
            return response()->json([
                'message'=>'this bill is already returned',
            ]);
        }

        if($pharmacy->pharmacy_wallet < $bill->bill_total){
            return response()->json([
                'message'=>'not enought money in wallet',
            ]);
        }

        $bill->update([
            'is_return'=>1,
        ]);

        $pharmacy->update([
            'pharmacy_wallet'=>$pharmacy->pharmacy_wallet-$bill->bill_total,
        ]);

        $items = $bill->item;

        foreach($items as $item){
            $content = Content::find($item->content_id);
            $content->update([
                'quantity'=>$content->quantity+$item->quantity,
            ]);
        }

        return response()->json([
            'success'=>'this bill returned successfully',
        ],200);
    }

    //get orders
    public function get_orders(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $orders = $pharmacy->corder;

        return response()->json([
            'orders'=>$orders,     
        ],200);
    }

    //get order items
    public function get_order_items($order_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $order = Corder::find($order_id);

        if(!$order || $order->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this order not exist',
            ]);
        }

        $items = $order->icorder;
        $doctor = Doctor::find($order->doctor_id);
        $cdelivery = Cdelivery::find($order->cdelivery);
        
        return response()->json([
            'doctor'=>$doctor,
            'items'=>$items,     
            'delivery_compsny'=>$cdelivery,
        ],200);
    }

    //get order item information
    public function get_order_item_information($order_id,$material_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $order = Order::find($order_id);
        $material = Material::find($material_id);

        return response()->json([
            'material'=>$material,
            'disease'=>$material->disease,
            'component'=>$material->component,
            'component_information'=>$material->component_info
        ]);
    }

    //accept client order
    public function accept_client_order(Request $request, $order_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $mes_pharmacy = $request->input('mes_pharmacy');
        $order = Corder::find($order_id);

        if(!$order || $pharmacy->id != $order->pharmacy_id){
            return response()->json([
                'error'=>'this order not exist',
            ]);
        }

        if($order->is_accept){
            return response()->json([
                'message'=>'already accepted',
            ]);
        }

        $order->update([
            'is_accept'=>1,
            'mes_pharmacy'=>$mes_pharmacy,
        ]);

        return response()->json([
            'message'=>'accepted successfully',     
        ],200);
    }
    //reject client order
    public function reject_client_order(Request $request, $order_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $mes_pharmacy = $request->input('mes_pharmacy');
        $order = Corder::find($order_id);

        if(!$order || $pharmacy->id != $order->pharmacy_id){
            return response()->json([
                'error'=>'this order not exist',
            ]);
        }

        if($order->is_accept == 1){
            return response()->json([
                'message'=>'already accepted',
            ]);
        }

        $order->update([
            'is_accept'=>0,
            'mes_pharmacy'=>$mes_pharmacy,
        ]);

        return response()->json([
            'message'=>'rejected successfully',     
        ],200);
    }

    //create stock
    public function create_stock(StockRequest $request){
        $pharmacy = Auth::guard('pharmacy_api')->user();

        $location_id = $request->input('location_id');
        $stock_name = $request->input('stock_name');
        $space = $request->input('space');
        $temp = $request->input('temp');

        $location = Location::find($location_id);

        if(!$location){
            return response()->json([
                'error'=>'this location not exist',
            ]);
        }

        $stock = Stock::create([
            'pharmacy_id'=>$pharmacy->id,
            'location_id'=>$location_id,
            'stock_name'=>$stock_name,
            'space'=>$space,
            'temp'=>$temp,
        ]);

        return response()->json([
            'success'=>'stock created successfully',
            'stock'=>$stock,
        ],200);
    }

    //get pharmacy stocks
    public function get_stocks(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $stocks = $pharmacy->stock;

        return response()->json([
            'stocks'=>$stocks,
        ],200);
    }

    //add item to pharmacy stock
    public function add_stock_item($stock_id, StockItemRequest $request){
        $pharmacy =  Auth::guard('pharmacy_api')->user();
        $stock = Stock::find($stock_id);

        if(!$stock || $stock->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this stock not exist',
            ]);
        }

        $content_id = $request->input('content_id');
        $quantity = $request->input('quantity');

        $content = Content::find($content_id);

        if(!$content || $content->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this content not exist',
            ]);
        }

        if($quantity> $content->quantity){
            return response()->json([
                'message'=>'not enought materials',
            ]);
        }

        $content->update([
            'quantity'=>$content->quantity-$quantity,
        ]);

        $item = Istock::where(['stock_id'=>$stock_id,'content_id'=>$content_id])->first();

        if(!$item){
            Istock::create([
                'content_id'=>$content_id,
                'stock_id'=>$stock_id,
                'quantity'=>$quantity,
            ]);
        }else{
            $item->update([
                'quantity'=>$item->quantity+$quantity,
            ]);
        }

        return response()->json([
            'success'=>'this item added successfully',
        ],200);
    }

    //return item from stock
    public  function return_stock_item($stock_id,StockItemRequest $request){
        $pharmacy =  Auth::guard('pharmacy_api')->user();
        $stock = Stock::find($stock_id);

        if(!$stock || $stock->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this stock not exist',
            ]);
        }

        $content_id = $request->input('content_id');
        $quantity = $request->input('quantity');

        $item = Istock::where(['stock_id'=>$stock_id,'content_id'=>$content_id])->first();

        if(!$item){
            return response()->json([
                'error'=>'this item not exist',
            ]);
        }

        $content = Content::find($content_id);

        if(!$content || $content->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this content not exist',
            ]);
        }

        if($quantity> $item->quantity){
            return response()->json([
                'message'=>'not enought materials',
            ]);
        }
        
        $item->update([
            'quantity'=>$item->quantity-$quantity,
        ]);

        $content->update([
            'quantity'=>$content->quantity+$quantity,
        ]);

        return response()->json([
            'success'=>'this material returned successfully',
        ],200);
    }

    //get waste batch
    public function get_waste_batchs(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $batchs = $pharmacy->batch->where('expiry_date','<',date('Y-m-d'));
        $contents = [];
        for($i=0;$i<$batchs->count();$i++){
            $contents[$i] = $batchs[$i]->contents;
        }
        return response()->json([
           'waste_batchs'=>$batchs, 
           'contents'=>$contents,
        ]);
    }

    //get waste batch content
    public function get_waste_batch_content($batch_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();   
        $batch = Batch::find($batch_id);

        if(!$batch || $batch->expiry_date > date('Y-m-d')){
            return response()->json([
                'error'=>'this batch not exist',
            ]);
        }

        return response()->json([
            'waste_contents'=>$batch->content,
        ]);
    }

    //create waste
    public function create_waste(WasteRequest $request){
        $pharmacy = Auth::guard('pharmacy_api')->user();

        $w_descriptions = $request->input('w_descriptions');
        $waste_number = $request->input('wastes_number');
        $date = date('Y-m-d');

        $waste = Waste::create([
           'pharmacy_id'=>$pharmacy->id,
           'wastes_number'=>$waste_number,
           'date'=>$date,
           'w_descriptions'=>$w_descriptions, 
        ]);

        return response()->json([
            'message'=>'waste created successfully',
            'waste'=>$waste,
        ]);
    }

    //add item to waste
    public function add_waste_item($waste_id,WasteItemRequest $request){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $waste = Waste::find($waste_id);

        if(!$waste || $waste->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this waste not exist',
            ]);
        }

        $content_id = $request->input('content_id');
        $quantity = $request->input('quantity');

        $content = Content::find($content_id);

        if(!$content || $content->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this content not exist',
            ]);
        }

        if($content->quantity < $quantity){
            return response()->json([
                'error'=>'not enought material',
            ]);
        }

        $items = $waste->item;
        $material = Material::find($content->material_id);

        $total_price = $quantity*$material->pp;
        $check_content = $waste->content->where('material_id',$content->material_id)->first();
        if($items == '[]'){
            WasteItem::create([
                'content_id'=>$content_id,
                'waste_id'=>$waste_id,
                'quantity'=>$quantity,
                'total_pp'=>$total_price,
            ]);

            $waste->update([
                'iwaste_number'=>$waste->iwaste_number+1,
                'waste_total'=>$total_price,
            ]);

            $content->update([
                'quantity'=>$content->quantity-$quantity,
            ]);

            
            return response()->json([
                'success'=>'this item added successfully',
            ]);
        }else if(!$check_content){
             WasteItem::create([
                'content_id'=>$content_id,
                'waste_id'=>$waste_id,
                'quantity'=>$quantity,
                'total_pp'=>$total_price,
            ]);

            $waste->update([
                'iwaste_number'=>$waste->iwaste_number+1,
                'waste_total'=>$waste->waste_total+$total_price,
            ]);

            $content->update([
                'quantity'=>$content->quantity-$quantity,
            ]);
            
            return response()->json([
                'success'=>'this item added successfully',
            ]);
        }
        else{
            for($i=0;$i<$items->count();$i++)
                 if($items[$i]->content_id == $content_id){
                    $items[$i]->update([
                        'quantity'=>$items[$i]->quantity+$quantity,
                        'total_pp'=>$items[$i]->total_pp+$total_price,
                    ]);  

                    $content->update([
                        'quantity'=>$content->quantity-$quantity,
                    ]);

                    $waste->update([
                        'waste_total'=>$waste->waste_total+$total_price,
                    ]);
                
                    return response()->json([
                        'success'=>'this item added successfully',
                    ]);
                }
            }

        WasteItem::create([
            'content_id'=>$content_id,
            'waste_id'=>$waste_id,
            'quantity'=>$quantity,
            'total_pp'=>$total_price,
        ]);
        $waste->update([
            'waste_total'=>$waste->waste_total+$total_price,
        ]);

        $content->update([
            'quantity'=>$content->quantity-$quantity,
        ]);

        return response()->json([
            'success'=>'this item added successfully',
        ]);
    }

    //get all companies
    public function get_companies(){
        $companies = Company::get();

        return response()->json([
            'companies'=>$companies,
        ],200);
    }

    //get material information
    public function get_material_information($material_id){
        $material = Material::find($material_id);

        if(!$material || $material->is_active == 0){
            return response()->json([
                'error'=>'this material not exist',
            ]);
        }

        return response()->json([
            'diseases'=>$material->disease,
            'components'=>$material->component,
            'batch'=>$material->batch,
        ]);
    }

    //send message to employee
    public function send_message($employee_id, MessageRequest $request){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $employee = Employee::find($employee_id);

        $message = $request->input('message');

        if(!$employee || $employee->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this employee not exist',
            ]);
        }

        Message::create([
            'pharmacy_id'=>$pharmacy->id,
            'employee_id'=>$employee_id,
            'message'=>$message,
        ]);

        return response()->json([
            'success'=>'sent ssuccessfully',
            'message'=>$message,
        ]);
    }

    //get messages with employee
    public function get_messages($employee_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $employee = Employee::find($employee_id);

        if(!$employee || $employee->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this employee not exist',
            ]);
        }


        $messages = $pharmacy->message->where('employee_id',$employee_id);

        return response()->json([
            'messages'=>$messages,     
        ]);
    }

    //get pharmacy contents
    public function get_contents(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $contents = $pharmacy->content;

        return response()->json([
            'contents'=> $contents,
        ]);
    }

    //get pharmacy content with info
    public function get_content_info(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $contents = $pharmacy->content;

        $material = [];
        $quantity = [];
        for($i=0;$i<$contents->count();$i++){
            $check_material = Material::find($contents[$i]->material_id);
            $j = 0;
            for($j;$j<count($material);$j++){
                if($material[$j]->id == $check_material->id){
                    $quantity[$j] = $contents[$i]-> quantity+=$contents[$j]->quantity;
                    break;
                }
            }

            if($j == count($material)){
                $material[$i] = Material::find($contents[$i]->material_id);
                $quantity[$i] = $contents[$i]->quantity;
            }
        }

        return response()->json([
           'materials'=>$material,         
           'quantity'=>$quantity,
        ]);
    }

    //get content with details
    public function get_content_detail($material_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $material = Material::find($material_id);

        $contents = $material->content->where('pharmacy_id',$pharmacy->id);
        $batchs = [];

        for($i=0;$i<$contents->count();$i++){
            $batchs[$i] = Batch::find($contents[$i]->batch_id);
        }

        return response()->json([
            'contents'=>$contents,   
            'batchs'=>$batchs,      
        ]);
    }

    //get company batchs of material
    public function get_company_batchs($material_id){

        $material = Material::find($material_id);
        if(!$material){
            return response()->json([
                'error'=>'this material not exists',
            ]);
        }
        $batchs = Batch::where(['material_id'=>$material_id,'is_active'=>1])->get();
        
        return response()->json([
            'batchs'=>$batchs,
        ]);
    }

    //get stock items
    public function get_stock_items($stock_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $stock = Stock::find($stock_id);

        if(!$stock || $stock->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this stock not exist',     
            ]);
        }

        $items = $stock->istock;
        $contents = $stock->content;
        $materials = [];

            for($i=0 ; $i<$contents->count() ; $i++){
                $check_material = Material::find($contents[$i]->material_id);
                if(!$materials){
                    $materials[$i] = $check_material;
                }else{
                for($j=0 ; $j<count($materials) ; $j++){
                    if($check_material->id == $materials[$j]->id){
                        break;
                    }
                }
                if($j == count($materials)){
                $materials[$i] = $check_material;
                }
            }
            }

        // for($i=0 ; $i<$contents->count() ; $i++){
        //     $materials[$i] = Material::find($contents[$i]->material_id);
        // }
        return response()->json([
            'items'=>$items,   
            'materials'=>$materials,
            //'contents'=>$stock->content,      
        ]);
    }

    //get stock item information
    public function get_stock_item_information($stock_id,$material_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $stock = Stock::find($stock_id);
        $material = Material::find($material_id);
        $contents = $material->content;
        //$contents = $material->content->where('pharmacy_id',$pharmacy->id);
        $disease = $material->disease;
        $component = $material->component;
        $component_info = $material->component_info;

        $items = [];
        for($i=0;$i<$contents->count();$i++){
            $items[$i] = Istock::where('content_id',$contents[$i]->id)->first();
        }

        return response()->json([
            'material'=>$material,
            'items'=> $items,
            'disease'=>$disease,
            'component'=>$component,
            'component_information'=>$component_info,
        ]);
    }

    //get requires
    public function get_requires(){
        $pharmacy = Auth::guard('pharmacy_api')->user();

        $requires = $pharmacy->require;
        // $materials = $pharmacy->material;
        // $employees = $pharmacy->employee_require;

        return response()->json([
            'requires'=>$requires,
            // 'materials'=>$materials,      
            // 'employees'=>$employees,   
        ]);
    }

    //get require information
    public function get_require_information($require_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $require = RequireModel::find($require_id);
        $employee = Employee::find($require->employee_id);
        $material = Material::find($require->material_id);
        
        return response()->json([
            'require'=>$require,
            'employee'=>$employee,
            'material'=>$material,
        ]);
    }

    //search employee by name
    public function search_employee(Request $request){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        
        $employee_name = $request->input('employee_name');

        $employee = Employee::where('pharmacy_id',$pharmacy->id)->where('employee_name','like','%'.$employee_name.'%')->get();

        return response()->json([
            'employee'=>$employee,
        ]);
    }

    //remove stock
    public function remove_stock($stock_id){
        $stock = Stock::find($stock_id);
        $items = $stock->istock;

        foreach($items as $item){
            $item->delete();
        }

        $stock->delete();

        return response()->json([
            'success'=>'deleted successfully',
        ]);
    }

    //get all materials in pharmacy
    public function get_all_materials(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $contents = $pharmacy->content;
        $stocks = $pharmacy->stock;

        $c_items = [];
        $s_items = [];

        for($i=0 ; $i<$stocks->count();$i++){
            $s_items[$i] = $stocks[$i]->istock;
        }

        $items = [];

        for($i=0;$i<$contents->count();$i++){
            for($j=0;$j<count($items);$j++){
                if($items[$j]->material_id == $contents[$i]){
                    $items[$j]->quantity += $contents[$i]->quantity;
                    break;
                }
            }

            if($i == $j){
                $items[$i] = $contents[$i];;
            }

        }

        for($i=0;$i<$contents->count();$i++){
            for($j=0;$j<count($items);$j++){
                if($items[$j]->material_id == $c_items[$i]->material_id){
                    $items[$j]->quantity += $c_items[$i]->quantity;
                    break;
                }
            }

            if($i == $j){
                $items[$i] = Material::find($c_items[$i]->material_id);
            }
        }

        return response()->json([
            'materials'=>$items,
            //'s'=>$s_items,
        ]);
    }

    //check orders
    public function check_orders(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $orders = $pharmacy->order->where('is_accept',1);
        foreach($orders as $order){
            if($order->send_date == date('Y-m-d')){
            $items = $order->order_item;

            foreach($items as $item){
                $content = Content::where(['pharmacy_id'=>$pharmacy->id,'batch_id'=>$item->batch_id,'material_id'=>$item->material_id])->first();
                if(!$content ){
                    Content::create([
                        'pharmacy_id'=>$pharmacy->id,
                        'material_id'=>$item->material_id,
                        'batch_id'=>$item->batch_id,
                        'quantity'=> $item->quantity,
                        'min'=>0,
                        'max'=>1,
                    ]);
                }else{
                    $content->update([
                        'quantity'=>$content->quantity+$item->quantity,
                    ]);
                }
            }
            }
    }
    return response()->json([
        'success'=>'updated successfully',
    ]);
    }

    //get wastes bill
    public function get_wastes(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $wastes = $pharmacy->waste;

        return response()->json([
            'wastes'=>$wastes,
        ]);
    }

    //get waste bill information
    public function get_waste_information($waste_id){
        $waste = Waste::find($waste_id);
        $items = $waste->item;

        return response()->json([
            'waste'=>$waste,
            'items'=>$items,
        ]);
    }

    //get waste item information
    public function get_waste_item_information($waste_id,$item_id){
        $item = WasteItem::find($item_id);
        $content = Content::find($item->content_id);
        $material = Material::find($content->material_id);

        return response()->json([
            'item'=>$item,
            'content'=>$content,
            'material'=>$material,
        ]);
    }

    //get all pharmacy materials
    public function get_all_pharmacy_materials(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $contents = $pharmacy->content;
        $stocks = $pharmacy->stock;
        
        // $stock_contents = [];
        // for($i=0;$i<$stocks->count();$i++){
        //     $stock_contents[$i] = $stocks[$i]->content;
        // }

        $pharmacy_items = [];
        $quantity = [];

        $count1 = 0;
        for($i=0;$i<$contents->count();$i++){
            $material = Material::find($contents[$i]->material_id);
            if(!$pharmacy_items){
                $pharmacy_items[$count1] = $material;
                $quantity[$count1] = $contents[$i]->quantity;
                $count1++;
                continue;
            }
                $j = 0;
                for($j=0;$j<count($pharmacy_items);$j++){
                    if($pharmacy_items[$j]->id == $material->id){
                        $quantity[$j] += $contents[$i]->quantity;
                        break;
                    }
                }

                if($j == count($pharmacy_items)){
                    $pharmacy_items[$count1] = $material;
                    $quantity[$count1] = $contents[$i]->quantity;
                    $count1++;
                }
        }
        // return response()->json([
        //     'contents'=>$pharmacy_items,
        //     'quantity'=>$quantity,
        // ]);
        $count2 = 0;
        for($i=0;$i<$stocks->count();$i++){
            $stock_contents = $stocks[$i]->content;
            $stock_items = $stocks[$i]->istock;
        for($i=0;$i<count($stock_contents);$i++){
            $material = Material::find($stock_contents[$i]->material_id);
            // if(!$pharmacy_items){
            //     $pharmacy_items[$i] = $material;
            //     $quantity[$i] = $stock_items[$i]->quantity;
            //     continue;
            // }
                $j = 0;
                for($j=0;$j<count($pharmacy_items);$j++){
                    if($pharmacy_items[$j]->id == $material->id){
                        $quantity[$j] += $stock_items[$i]->quantity;
                        break;
                    }
                }

                if($j == count($pharmacy_items)){
                    $pharmacy_items[$count2] = $material;
                    $quantity[$count2] = $stock_items[$i]->quantity;
                    $count2++;
                }
            }
        }

        return response()->json([
            'contents'=>$pharmacy_items,
            'quantity'=>$quantity,
        ]);
    }

    //get material details
    public function get_material_details($material_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $material = Material::find($material_id);
        $pharmacy_contents = $pharmacy->content->where('material_id',$material->id);
        
        $stock_items = [];
        $stocks = [];
        for($i=0;$i<$pharmacy_contents->count();$i++){
            $stock_items[$i] = $pharmacy_contents[$i]->istock;
            $stocks[$i] = $pharmacy_contents[$i]->stock;
        }

        return response()->json([
            'pharmacy_contents'=>$pharmacy_contents,
            'stock_items'=>$stock_items,
            'stocks'=>$stocks,
        ]);
    }
    
    //get minimum materials
    public function get_minimum(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $materials = $pharmacy->material;

        $minimums = [];
        $quantitys = [];
        $count = 0;
        foreach($materials as $material){
            $contents = $pharmacy->content->where('material_id',$material->id);
            $quantity = 0;
            $minimum = 0;
            foreach($contents as $content){
                $quantity+=$content->quantity;
                $minimum += $content->min;
            }

            $i = 0;
            for($i=0;$i<count($minimums);$i++){
                if($minimums[$i]->id == $material->id){
                    break;
                }
            }



            if($quantity<$minimum && $i == count($minimums)){
                $minimums[$count] = $material;
                $quantitys[$count] = $quantity;
                $count++;
            }else if($quantity>$minimum && !$minimums){
                $minimums[$count] = $material;
                $quantitys[$count] = $quantity;
                $count++;
            }
        }
        return response()->json([
            'minimums'=>$minimums,
            'quantity'=>$quantitys,
        ]);
    }
    //get maximum materials
    public function get_maximum(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $materials = $pharmacy->material;

        $maximums = [];
        $quantitys = [];
        $count = 0;
        foreach($materials as $material){
            $contents = $pharmacy->content->where('material_id',$material->id);
            $quantity = 0;
            $maximum = 0;
            foreach($contents as $content){
                $quantity+=$content->quantity;
                $maximum += $content->max;
            }

            $i = 0;
            for($i=0;$i<count($maximums);$i++){
                if($maximums[$i]->id == $material->id){
                    break;
                }
            }

            if($quantity>$maximum && $i == count($maximums)){
                $maximums[$count] = $material;
                $quantitys[$count] = $quantity;
                $count++;
            }else if($quantity>$maximum && !$maximums){
                $maximums[$count] = $material;
                $quantitys[$count] = $quantity;
                $count++;
            }
        }
        return response()->json([
            'maximums'=>$maximums,
            'quantity'=>$quantitys,
        ]);
    }

    // get rates
    public function get_rates(){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $rates = $pharmacy->rate;
        
        $rating=0;
        
        foreach($rates as $rate){
            $rating += $rate->rating;
        }

        return response()->json([
           'rating'=>$rating/$rates->count(),        
        ]);
    }

    //add favorite material
    public function add_remove_favorite($material_id){
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $favorites = Favorite::get();
        
        foreach($favorites as $favorite){
            if($favorite->material_id == $material_id &&  $favorite->pharmacy_id == $pharmacy->id){
                $favorite->delete();

                return response()->json([
                    'success'=>'removed from favorite',         
                 ]);
            }
        }

        $favorite = Favorite::create([
            'pharmacy_id'=>$pharmacy->id,
            'material_id'=>$material_id,
        ]);

        return response()->json([
            'suuccess'=>'added favorite',         
         ]);
    }

    //add to favorite
    public function add_favorite(Request $request){
        $material_id = $request->input('material_id');
        $pharmacy = Auth::guard('pharmacy_api')->user();
        $favorites = Favorite::get();
        
        foreach($favorites as $favorite){
            if($favorite->material_id == $material_id &&  $favorite->pharmacy_id == $pharmacy->id){
                return response()->json([
                    'message'=>'added before',
                ]);
            }
        }
        $favorite = Favorite::create([
            'pharmacy_id'=>$pharmacy->id,
            'material_id'=>$material_id,
        ]);

        return response()->json([
            'suuccess'=>'added favorite',         
         ]);
    }

        //remove from favorite
        public function delete_favorite($favorite_id){
            $pharmacy = Auth::guard('pharmacy_api')->user();
            $favorites = Favorite::get();
            $favorite = Favorite::find($favorite_id);
            
            foreach($favorites as $favorite){
                if($favorite->id == $favorite_id &&  $favorite->pharmacy_id == $pharmacy->id){
                    $favorite->delete();
                    return response()->json([
                        'success'=>'delete success',
                    ]);
                }
            }
        }
    //get pharmacy favorite
    public function get_favorite(){
        $pharmacy = Auth::guard('pharmacy_api')->user();

        return response()->json([
            'favorite_materials'=>$pharmacy->favorite_material,
        ]);
    }

    //make report
    public function make_report($type, Request $request){
        $pharmacy = Auth::guard('pharmacy_api')->user();

        $from = $request->input('from');
        $to = $request->input('to');
        if($type == 1){
            $requires = RequireModel::where('pharmacy_id',$pharmacy->id)->where('date','>=',$from)->where('date','<=',$to)->get();
            if($requires == '[]'){
                return response()->json([
                    'empty'=>'no requires recently'
                ]);
            }
            $total = 0;

            foreach($requires as $require){
                $material = Material::find($require->material_id);
                $total += $material->pp * $require->quantity;
            }

            $report = Report::create([
                'is_require'=>1,
                'pharmacy_id'=>$pharmacy->id,
                'from'=>$from,
                'to'=>$to,
                'total'=>$total,
                'date'=> date('Y-m-d'),
            ]);

            return response()->json([
                'report'=>$report,
            ],200);
        }else if($type == 2){
            $orders = Order::where('is_accept',1)->where('pharmacy_id',$pharmacy->id)->where('date','>=',$from)->where('date','<=',$to)->get();
            if($orders == '[]'){
                return response()->json([
                    'empty'=>'no orders recently'
                ]);
            }

            $total = 0;

            foreach($orders as $order){
                $items = $order->order_item;
                foreach($items as $item){
                    $total += $item->total_price;
                }
            }

            $report = Report::create([
                'is_order'=>1,
                'pharmacy_id'=>$pharmacy->id,
                'from'=>$from,
                'to'=>$to,
                'total'=>$total,
                'date'=>date('Y-m-d'),
            ]);

            return response()->json([
                'report'=>$report,
            ]);
        }else if($type == 3){
            $bills = Bill::where(['is_return'=>0,'pharmacy_id'=>$pharmacy->id])->where('date','>=',$from)->where('date','<=',$to)->get();

            if($bills == '[]'){
                return response()->json([
                    'empty'=>'no bills recently',
                ]);
            }
            $total = 0;

            foreach($bills as $bill){
                $total += $bill->bill_total;
            }

            $report = Report::create([
                'is_bill'=>1,
                'pharmacy_id'=>$pharmacy->id,
                'from'=>$from,
                'to'=>$to,
                'total'=>$total,
                'date'=>date('Y-m-d'),
            ]);

            return response()->json([
                'report'=>$report,
            ]);
        }else if ($type == 4){
            $corders = Corder::where(['pharmacy_id'=>$pharmacy->id,'is_accept'=>1])->where('date','>=',$from)->where('date','<=',$to)->get();

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
                'pharmacy_id'=>$pharmacy->id,
                'from'=>$from,
                'to'=>$to,
                'total'=>$total,
                'date'=>date('Y-m-d'),
            ]);

            return response()->json([
                'report'=>$report,
            ]);
        }else if($type == 5){
            $wastes = Waste::where('pharmacy_id',$pharmacy->id)->where('date','>=',$from)->where('date','<=',$to)->get();

            if($wastes == '[]'){
                return response()->json([
                    'empty'=>'no wastes recently'
                ]);
            }

            $total = 0;

            foreach($wastes as $waste){
                $total = $waste->waste_total;
            }
            
            $report = Report::create([
                'is_waste'=>1,
                'pharmacy_id'=>$pharmacy->id,
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
}