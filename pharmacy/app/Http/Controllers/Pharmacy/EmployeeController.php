<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\BillItemRequest;
use App\Http\Requests\BillRequest;
use App\Http\Requests\EmployeeLoginRequest;
use App\Http\Requests\EmployeeRegisterRequest;
use App\Http\Requests\RequireRequest;
use App\Http\Requests\WasteItemRequest;
use App\Http\Requests\WasteRequest;
use App\Http\Requests\MessageRequest;
use App\Models\Batch;
use App\Models\Message;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Client;
use App\Models\Content;
use App\Models\Corder;
use App\Models\Employee;
use App\Models\Favorite;
use App\Models\Material;
use App\Models\Report;
use App\Models\Pharmacy;
use App\Models\RequireModel;
use App\Models\Waste;
use App\Models\WasteItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    //login employee
    public function login(EmployeeLoginRequest $request){
        $employee_email = $request->input('employee_email');
        $employee_password = $request->input('employee_password');

        $employee = Auth::guard('employee_api')->attempt(['email'=>$employee_email, 'password'=>$employee_password]);
        if(!$employee){
            return response()->json([
                'message'=>'error in password or in email',
            ]);
        }

        $employee = Auth::guard('employee_api')->user();
        $token = Auth::guard('employee_api')->login($employee);
            return response()->json([
                'message'=>'login successfully',
                'employee'=> $employee,
                'token'=> $token,
            ],200);
    }

    //logout employee
    public function logout(){
        Auth::guard('employee_api')->logout();
        return response()->json([
            'message'=>'logout successfully',
        ]);
    }

    //employee dashboard
    public function dashboardInvoked(){
        return [
            'employee'=> Auth::guard('employee_api')->user(),
        ];
    }

    //add pharmacy require
    public function add_require(RequireRequest $request){
        $employee = Auth::guard('employee_api')->user();

        $material_id = $request->input('material_id');
        $quantity = $request->input('quantity');
        $description = $request->input('description');
        $require_number = $request->input('require_number');
        $date = date('Y,m,d');
        $material = Material::find($material_id);

        if(!$material){
            return response()->json([
                'error'=>'this material not exist',
            ]);
        }

        $check_require = RequireModel::where('require_number',$require_number)->first();
        if($check_require){
            return response()->json([
                'error'=>'this require number already used',
            ]);
        }

        RequireModel::create([
            'pharmacy_id'=>$employee->pharmacy_id,
            'employee_id'=>$employee->id,
            'material_id'=>$material_id,
            'quantity'=>$quantity,
            'description'=>$description,
            'date'=>$date,
            'require_number'=>$require_number,
        ]);

        return response()->json([
            'success'=>'require sent successfully',
        ],200);
    } 

    //get require 
    public function get_require(){
        $employee = Auth::guard('employee_api')->user();
        $requires = $employee->requires;

        return response()->json([
            'requires'=>$requires, 
        ],200);
    }

    //get require information
    public function get_require_info($require_id){
        $employee = Auth::guard('employee_api')->user();
        $require = RequireModel::find($require_id);
        $pharmacy = Pharmacy::find($employee->pharmacy_id);
        $material = Material::find($require->material_id);
        
        return response()->json([
            'require'=>$require,
            'material'=>$material,
        ]);
    }

    //create bill
    public function create_bill(BillRequest $request){
        $employee = Auth::guard('employee_api')->user();
        
        $client_id = $request->input('client_id');
        $client_name = $request->input('client_name');
        $date = date('Y-m-d');
        $bill_number = $request->input('bill_number');
        $is_delivery = $request->input('is_delivery');
        $delivery_cost = $request->input('delivery_cost');
        $is_return = $request->input('is_return');

        $client = Client::find($client_id);

        if(!$client && !$client_name){
            return response()->json([
                'error'=>'this client not exist',
            ]);
        }

        $bill = Bill::create([
            'pharmacy_id'=>$employee->pharmacy_id,
            'employee_id'=>$employee->id,
            'client_id'=>$client_id,
            'client_name'=>$client_name,
            'date'=>$date,
            'bill_number'=>$bill_number,
            'is_delivery'=>$is_delivery,
            'delivery_cost'=>$delivery_cost,
            'is_return'=>$is_return,
        ]);

        if($bill->is_delivery){
            $bill->update([
                'bill_total'=>$delivery_cost,
            ]);
        }

        return response()->json([
            'success'=>'bill created successfully',
            'bill'=>$bill,
        ],200);
    }

    //add item to bill
    public function add_bill_item($bill_id, BillItemRequest $request){
        $employee = Auth::guard('employee_api')->user();
        $pharmacy = Pharmacy::find($employee->pharmacy_id);
        $bill = Bill::find($bill_id);

        $content_id = $request->input('content_id');
        $quantity = $request->input('quantity');

        $content = Content::find($content_id);

        if(!$bill || $bill->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this order not exist',
            ]);
        }

        if(!$content || $content->pharmacy_id != $employee->pharmacy_id){
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

            $item->update([
                'quantity'=>$item->quantity+$quantity,
                'b_total'=>$item->b_total+$total_price,
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

    //get employee bills
    public function get_bills(){
        $employee = Auth::guard('employee_api')->user();
        $bills = $employee->bill;

        return response()->json([
            'bills'=>$bills,
        ],200);
    }

    //get employee bill items
    public function get_bill_items($bill_id){
        $employee = Auth::guard('employee_api')->user();
        $bill = Bill::find($bill_id);

        if(!$bill || $bill->pharmacy_id != $employee->pharmacy_id){
            return response()->json([
                'error'=>'this bill not exist',
            ]);
        }

        $items = $bill->item;

        return response()->json([
            'items'=>$items,
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
        $employee = Auth::guard('employee_api')->user();
        $pharmacy = Pharmacy::find($employee->pharmacy_id);
        $bill = Bill::find($bill_id);

        if(!$bill || $bill->pharmacy_id != $employee->pharmacy_id || $employee->id != $bill->employee_id){
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
        $employee = Auth::guard('employee_api')->user();
        $pharmacy = Pharmacy::find($employee->pharmacy_id);
        $orders = $pharmacy->corder;

        return response()->json([
            'orders'=>$orders,     
        ],200);
    }

    //get order items
    public function get_order_items($order_id){
        $employee = Auth::guard('employee_api')->user();
        $pharmacy = Pharmacy::find($employee->pharmacy_id);
        $order = Corder::find($order_id);

        if(!$order || $order->pharmacy_id != $pharmacy->id){
            return response()->json([
                'error'=>'this order not exist',
            ]);
        }

        $items = $order->icorder;
        $materials = $order->material;

        return response()->json([
            'items'=>$items,
            'materials'=>$materials,    
        ],200);
    }

    //accept client order
    public function accept_client_order(Request $request, $order_id){
        $employee = Auth::guard('employee_api')->user();
        $pharmacy = Pharmacy::find($employee->pharmacy_id);

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
        $employee = Auth::guard('employee_api')->user();
        $pharmacy = Pharmacy::find($employee->pharmacy_id);
        
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

        //get waste batch
        public function get_waste_batchs(){
            $employee = Auth::guard('employee_api')->user();
            $pharmacy = Pharmacy::find($employee->pharmacy_id);
            $batchs = $pharmacy->batch->where('expiry_date','<',date('Y-m-d'));
            $contents = [];
            for($i=0;$i<$batchs->count();$i++){
                $contents[$i] = $batchs[$i]->content;
            }
            return response()->json([
               'waste_batchs'=>$batchs, 
                'contents'=>$contents,
            ]);
        }
    
        //get waste batch content
        public function get_waste_batch_content($batch_id){
            $employee = Auth::guard('employee_api')->user();   
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
            $employee = Auth::guard('employee_api')->user();
    
            $w_descriptions = $request->input('w_descriptions');
            $waste_number = $request->input('wastes_number');
            $date = date('Y-m-d');
    
            $waste = Waste::create([
               'pharmacy_id'=>$employee->pharmacy_id,
               'employee_id'=>$employee->id,
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
        $employee = Auth::guard('employee_api')->user();
        $pharmacy = Pharmacy::find($employee->pharmacy_id);
        $waste = Waste::find($waste_id);

        if(!$waste || $waste->pharmacy_id != $pharmacy->id || $waste->employee_id != $employee->id){
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

    //send message to pharmacy
    public function send_message(MessageRequest $request){
        $employee = Auth::guard('employee_api')->user();
        
        $message = $request->input('message');

        Message::create([
            'pharmacy_id'=>$employee->pharmacy_id,
            'employee_id'=>$employee->id,
            'message'=>$message,
        ]);

        return response()->json([
            'success'=>'sent successfully',
            'message'=>$message,
        ]);
    }

    //get messages with pharmacy
    public function get_messages(){
        $employee = Auth::guard('employee_api')->user();

        $messages = $employee->message->where('pharmacy_id',$employee->pharmacy_id);

        return response()->json([
            'messages'=>$messages,     
        ]);
    }

    //make report
    public function make_report($type, Request $request){
        $employee = Auth::guard('employee_api')->user();

        $from = $request->input('from');
        $to = $request->input('to');
        if($type == 1){
            $requires = RequireModel::where('employee_id',$employee->id)->where('date','>=',$from)->where('date','<=',$to)->get();
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
                'pharmacy_id'=>$employee->pharmacy_id,
                'employee_id'=>$employee->id,
                'from'=>$from,
                'to'=>$to,
                'total'=>$total,
                'date'=> date('Y-m-d'),
            ]);

            return response()->json([
                'report'=>$report,
            ],200);
        }else if($type == 2){
            $bills = Bill::where(['is_return'=>0,'employee_id'=>$employee->id])->where('date','>=',$from)->where('date','<=',$to)->get();

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
                'pharmacy_id'=>$employee->pharmacy_id,
                'employee_id'=>$employee->id,
                'from'=>$from,
                'to'=>$to,
                'total'=>$total,
                'date'=>date('Y-m-d'),
            ]);

            return response()->json([
                'report'=>$report,
            ]);
        }else if ($type == 4){
            $corders = Corder::where(['employee_id'=>$employee->id,'is_accept'=>1])->where('date','>=',$from)->where('date','<=',$to)->get();

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
                'pharmacy_id'=>$employee->pharmacy_id,
                'employee_id'=>$employee->id,
                'from'=>$from,
                'to'=>$to,
                'total'=>$total,
                'date'=>date('Y-m-d'),
            ]);

            return response()->json([
                'report'=>$report,
            ]);
        }else if($type == 5){
            $wastes = Waste::where('employee_id',$employee->id)->where('date','>=',$from)->where('date','<=',$to)->get();

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
                'pharmacy_id'=>$employee->pharmacy_id,
                'employee_id'=>$employee->id,
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