<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\BatchRequest;
use App\Http\Requests\CompanyEditRequest;
use App\Http\Requests\CompanyLoginRequest;
use App\Http\Requests\CompanyRegisterRequest;
use App\Http\Requests\DiseaseRequest;
use App\Http\Requests\MaterialEditRequest;
use App\Http\Requests\MaterialRequest;
use App\Http\Requests\SupplierEditRequest;
use App\Http\Requests\SupplierRequest;
use App\Models\Batch;
use App\Models\Company;
use App\Models\Component;
use App\Models\ComponentMaterial;
use App\Models\Content;
use App\Models\Disease;
use App\Models\DiseaseMedicine;
use App\Models\Location;
use App\Models\Material;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pharmacy;
use App\Models\Report;
use App\Models\Supplier;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    //create company
    public function register(CompanyRegisterRequest $request){
        $companies = Company::get();

        $location_id = $request->input('location_id');
        $company_name = $request->input('company_name');
        $company_phone = $request->input('company_phone');
        $email = $request->input('email');
        $password = $request->input('password');
        $company_owner = $request->input('company_owner');
        $establishment_no = $request->input('establishment_no');
        $company_image = $request->input('company_image');
        $company_address = $request->input('company_address');
        $line_phone = $request->input('line_phone');
        $es_image = $request->input('es_image');

        if(!$location_id || !$company_name || !$company_phone ||
         !$email || !$password || !$company_owner || !$establishment_no ||!$company_address || 
         !$line_phone || !$es_image){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
         }

         foreach($companies as $company){
            if($company->email == $email){
                return response()->json([
                    'error'=>'this email already exist',
                ]);
            }
            if($company->establishment_no == $establishment_no){
                return response()->json([
                    'error'=>'this establishment number already exist',
                ]);
            }
         }

        $location = Location::find($location_id);
        if(!$location){
            return response()->json([
                'error'=>'this location not exist',
            ]);
        }

        $company = Company::create([
            'location_id'=>$location_id,
            'company_address'=>$company_address,
            'company_name'=>$company_name,
            'company_phone'=>$company_phone,
            'line_phone'=>$line_phone,
            'email'=>$email,
            'password'=>Hash::make($password),
            'company_owner'=>$company_owner,
            'establishment_no'=>$establishment_no,
            'es_image'=>$es_image,
        ]);

        if($company_image){
            $company->update([
                'company_image'=>$company_image,
            ]);
        }

        $state = $location->state;

        return response()->json([
            'message'=>'company created successfully but in wait',
        ],200);
    }

    //login company
    public function login(CompanyLoginRequest $request){
        $email = $request->input('email');
        $password = $request->input('password');


        if(!$email | !$password){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }
        $company = Auth::guard('company_api')->attempt(['email'=>$email,'password'=>$password]);

        if(!$company){
            return response()->json([
                'error'=>'email or password is uncorrect',
            ]);
        }
        $company = Auth::guard('company_api')->user();
        if($company->is_active == 0){
            return response()->json([
                'message'=>'if sure your account not active call us: 095446565',
            ]);
        }
            $token = Auth::guard('company_api')->login($company);

            $location = Location::find($company->location_id);
            $state = $location->state;

            return response()->json([
                'message'=>'login successfully',
                'company'=>$company,
                'token'=>$token,
                'state'=>$state->state_name,
                'location'=>$location->location_name,
            ]);
    }

    //company dashboard
    public function dashboardInvoked(){
        $company = Auth::guard('company_api')->user();
        $token = Auth::guard('company_api')->login($company);
        $location = Location::find($company->location_id);
        $state = $location->state;
        
        return response()->json([
            'company'=>$company,
            'token'=>$token,
            'state'=>$state->state_name,
            'location'=>$location->location_name,
        ]);
    }

    //add material for company
    public function add_material(MaterialRequest $request){
        $materials = Material::get();

        $type_id = $request->input('type_id');
        $material_name = $request->input('material_name');
        $scientific_name = $request->input('scientific_name');
        $material_image = $request->input('material_image');
        $price_pharmacy = $request->input('price_pharmacy');
        $price_sell = $request->input('price_sell');
        $qr_code = $request->input('qr_code');
        $license_image = $request->input('license_image');

        if(!$type_id || !$material_name || !$scientific_name || !$material_image || !$price_pharmacy ||
        !$price_sell || !$qr_code || !$license_image){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        foreach($materials as $material){
            if($material->material_name  == $material_name){
                return response()->json([
                    'error'=>'this material  already exist',
                ]);
            }
        }

        $company = Auth::guard('company_api')->user();
        $type = Type::find($type_id);

        if(!$company){
            return response()->json([
                'error'=>'this company not exist',
            ]);
        }

        if(!$type){
            return response()->json([
                'error'=>'this type not exist',
            ]);
        }

        $material = Material::create([
            'company_id'=>$company->id,
            'type_id'=>$type_id,
            'material_name'=>$material_name,
            'scientific_name'=>$scientific_name,
            'material_image'=>$material_image,
            'pp'=>$price_pharmacy,
            'ps'=>$price_sell,
            'qr_code'=>$qr_code,
            'license_image'=>$license_image,
        ]);

        return response()->json([
            'message'=>'material added successfully and in wait',
        ],200);
    }

    //get materials of company
    public function get_materials(){
        $company = Auth::guard('company_api')->user();
        if(!$company){
            return response()->json([
                'error'=>'this company not exist',
            ]);
        }

        $materials = $company->material;

        return response()->json([
            'materials'=>$materials,
        ],200);
    }

    //logout from company
    public function logout(){
        Auth::guard('company_api')->logout();
        return response()->json([
            'success'=>'logout successfully',
        ]);
    }

    //add component to material
    public function add_component($material_id, Request $request){
        $material = Material::find($material_id);
        $company = Auth::guard('company_api')->user();

        $component_id = $request->input('component_id');
        $component_quantity = $request->input('component_quantity');

        if(!$component_id || !$component_quantity){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        $component = Component::find($component_id);

        if(!$material || $material->company_id != $company->id){
            return response()->json([
                'error'=>'this material not exist',
            ]);
        }

        if(!$component){
            return response()->json([
                'error'=>'this component not exist', 
            ]);
        }

        ComponentMaterial::create([
            'material_id'=>$material_id,
            'component_id'=>$component_id,
            'quantity_used'=>$component_quantity,
        ]);

        return response()->json([
           'success'=>'this component added successfully',     
        ],200);
    }

    //add disease to material
    public function add_disease($material_id, Request $request){
        $material = Material::find($material_id);
        $company = Auth::guard('company_api')->user();

        $disease_id = $request->input('disease_id');

        if(!$disease_id){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        $disease = Component::find($disease_id);

        if(!$material || $material->company_id != $company->id){
            return response()->json([
                'error'=>'this material not exist',
            ]);
        }

        if(!$disease){
            return response()->json([
               'error'=>'this disease not exist', 
            ]);
        }

        DiseaseMedicine::create([
            'material_id'=>$material_id,
            'disease_id'=>$disease_id,
        ]);
        return response()->json([
            'success'=>'this disease added successfully',
        ],200);
    }

    //edit material information
    public function edit_material($material_id, MaterialEditRequest $request){
        $material_image = $request->input('material_image');
        $price_pharmacy = $request->input('price_pharmacy');
        $price_sell = $request->input('price_sell');

        if(!$material_image || !$price_pharmacy ||!$price_sell){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        $company = Auth::guard('company_api')->user();
        $material = Material::find($material_id);

        if(!$material || $material->company_id != $company->id){
            return response()->json([
                'error'=>'this material not exist', 
            ]);
        }

        $material->update([
            'material_image'=>$material_image,
            'pp'=>$price_pharmacy,
            'ps'=>$price_sell,
        ]);

        return response()->json([
            'success'=>'updated successfully',
        ],200);
    }

    //edit material disease
    public function edit_material_disease($material_id, $disease_id,Request $request){
        $new_disease_id = $request->input('disease_id');

        if(!$new_disease_id){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }

        $material = Material::find($material_id);
        $company = Auth::guard('company_api')->user();
        $disease = Disease::find($disease_id);
        $new_disease = Disease::find($new_disease_id);

        if(!$material || $material->company_id != $company->id){
            return response()->json([
                'error'=>'this material not exist',
            ]);
        }

        if(!$disease){
            return response()->json([
                'error'=>'this disease not exist',
            ]);
        }
        if(!$new_disease){
            return response()->json([
                'error'=>'this new disease not exist',
            ]);
        }

        $medicine_disease = DiseaseMedicine::where(['material_id'=>$material_id,'disease_id'=>$disease_id])->first();
        if(!$medicine_disease){
            return response()->json([
                'error'=>'this new disease not exist',
            ]);
        }

        if($medicine_disease->disease_id == $new_disease_id){
            return response()->json([
                'error'=>'this new disease already exist for this material',
            ]);
        }
        $medicine_disease->update([
            'disease_id'=>$new_disease_id,
        ]);

        return response()->json([
            'success'=>'successfully',
        ],200);
    }

    //edit company information
    public function edit_company(CompanyEditRequest $request){
        $company = Auth::guard('company_api')->user();

        $company_phone = $request->input('company_phone');
        $password = $request->input('password');
        $company_owner = $request->input('company_owner');
        $company_image = $request->input('company_image');
        $old_password = $request->input('old_password');
        $line_phone = $request->input('line_phone');

        if(!$company_phone || !$password || !$company_owner || !$line_phone || $company_image){
           return response()->json([
               'error'=>'there are empty fields',
           ]);
        }

        if(!Hash::check($old_password,$company->password)){
            return response()->json([
                'error'=>'uncorrect password',
            ]);
        }

        if($company->is_active == 0){
            return response()->json([
                'message'=>'your company not active',
            ]);
        }

        if(!$password){
            $company->update([
                'company_phone'=>$company_phone,
                'line_phone'=>$line_phone,
                'company_owner'=>$company_owner,
                'company_image'=>$company_image,
            ]);
            return response()->json([
                'message'=>'updated successfully',
            ]);
        }

        $company->update([
            'company_phone'=>$company_phone,
            'line_phone'=>$line_phone,
            'password'=>Hash::make($password),
            'company_owner'=>$company_owner,
            'company_image'=>$company_image,
        ]);

        return response()->json([
            'message'=>'updated successfully',
        ]);
    }

    //add supplier to company
    public function add_supplier(SupplierRequest $request){
        $company = Auth::guard('company_api')->user();
        $suppliers = Supplier::get();

        $location_id = $request->input('location_id');
        $supplier_address = $request->input('supplier_address');
        $supplier_name = $request->input('supplier_name');
        $supplier_image = $request->input('supplier_image');
        $supplier_phone = $request->input('supplier_phone');
        $supplier_description = $request->input('supplier_description');
        $national_number = $request->input('national_number');

        if(!$location_id || !$supplier_address || !$supplier_name || !$supplier_phone ||
         !$supplier_description || !$national_number){
            return response()->json([
                'error'=>'there are empty fields',
            ]); 
        }

        foreach($suppliers as $supplier){
            if($supplier->national_number == $national_number){
                return response()->json([
                    'error'=>'this national number already exist',
                ]);
            }
        }

        $location = Location::find($location_id);

        if(!$location){
            return response()->json([
                'error'=>'this location not exists',
            ]);
        }

        Supplier::create([
            'company_id'=>$company->id,
            'location_id'=>$location_id,
            'supplier_name'=>$supplier_name,
            'supplier_image'=>$supplier_image,
            'supplier_phone'=>$supplier_phone,
            'supplier_description'=>$supplier_description,
            'national_number'=>$national_number,
            'supplier_address'=>$supplier_address,
        ]);
        
        return response()->json([
            'success'=>'supplier added successfully',
        ],200);
    }

    //edit supplier information
    public function edit_supplier(SupplierEditRequest $request, $supplier_id){
        $company = Auth::guard('company_api')->user();
        $supplier = Supplier::find($supplier_id);

        if(!$supplier || $supplier->company_id != $company->id){
            return response()->json([
                'error'=>'this supplier not exist',
            ]);
        }
        
        $supplier_name = $request->input('supplier_name');
        $supplier_image = $request->input('supplier_image');
        $supplier_phone = $request->input('supplier_phone');
        $supplier_description = $request->input('supplier_description');

        if(!$supplier_name || !$supplier_phone || $supplier_description || $supplier_image){
           return response()->json([
               'error'=>'there are empty fields',
           ]); 
       }

        $supplier->update([
            'supplier_name'=>$supplier_name,
            'supplier_image'=>$supplier_image,
            'supplier_phone'=>$supplier_phone,
            'supplier_description'=>$supplier_description,
        ]);

        return response()->json([
            'success'=>'updated successfully',
        ]);
    }

    //remove supplier from company
    public function remove_supplier($supplier_id){
        $company = Auth::guard('company_api')->user();
        $supplier = Supplier::find($supplier_id);

        if(!$supplier || $supplier->company_id != $company->id){
            return response()->json([
                'error'=>'this supplier not exist',
            ]);
        }
        
        $supplier->delete();

        return response()->json([
            'success'=>'removed successfully',
        ],200);
    }

    //add material to batch
    public function add_batch($material_id, BatchRequest $request){
        $company = Auth::guard('company_api')->user();
        $material = Material::find($material_id);

        $batch = $request->input('batch');
        $quantity = $request->input('quantity');
        $expiry_date = $request->input('expiry_date');
        $description = $request->input('description');
        $is_active = $request->input('is_active');

        if(!$batch || !$quantity || !$expiry_date || !$description || !$is_active){
            return response()->json([
                'error'=>'there are empty fields',
            ]);
        }
    

        if(!$material || $material->company_id != $company->id){
            return response()->json([
                'error'=>'this material not exist',
            ]);
        }

        $check_batch = Batch::where(['batch'=>$batch,'material_id'=>$material_id])->first();

        if($check_batch){
            return response()->json([
                'error'=>'this batch number already used for this material',
            ]);
        }    

        if($material->is_active == 0){
            return response()->json([
                'message'=>'this material not activated yet',
            ]);
        }
        if($expiry_date<= date('Y-m-d')){
            return response()->json([
                'error'=>'this expiry date is invalid',
            ]);
        }

        $check_batch = Batch::where(['material_id'=>$material_id,'batch'=>$batch])->first();

        if($check_batch){
            return response()->json([
                'message'=>'this batch already exist',
            ]);
        }
        
        Batch::create([
            'material_id'=>$material_id,
            'batch'=>$batch,
            'quantity'=>$quantity,
            'expiry_date'=>$expiry_date,
            'description'=>$description,
            'is_active'=>$is_active,
        ]);

        return response()->json([
            'success'=>'batch added successfully',
        ]);
    }

    //get batch of material
    public function get_batch($material_id){
        $company = Auth::guard('company_api')->user();
        $material = Material::find($material_id);

        if(!$material || $material->company_id != $company->id){
            return response()->json([
                'error'=>'this material not exist',
            ]);
        }

        $batchs = $material->batch;

        return response()->json([
            'batchs'=> $batchs,
        ],200);
    }

    //get suppliers of company
    public function get_supplier(){
        $company = Auth::guard('company_api')->user();
        $suppliers = $company->supplier;

        return response()->json([
            'suppliers'=>$suppliers,
        ]);
    }

    //get supplier information
    public function get_supplier_information($supplier_id){
        $supplier = Supplier::find($supplier_id);
        return response()->json([
            'supplier'=> $supplier,
        ]);
    }

    //get all orders
    public function get_order(){
        $company = Auth::guard('company_api')->user();
        $orders = Order::where(['company_id'=>$company->id,'is_sent'=>1])->get();

        return response()->json([
            'orders'=>$orders,
        ]);
    }

    //get order items
    public function get_order_item($order_id){
        $company = Auth::guard('company_api')->user();
        $order = Order::find($order_id);

        if(!$order || $order->company_id != $company->id){
            return response()->json([
                'error'=> 'this order not exist',
            ]);
        }

        $items = $order->order_item;

        return response()->json([
            'items'=>$items,     
        ],200);
    }

    //get order item batch
    public function get_item_batch_details($order_id,$item_id){
        $company = Auth::guard('company_api')->user();
        $order = Order::find($order_id);
        $item = OrderItem::find($item_id);

        if(!$order || $order->company_id != $company->id){
            return response()->json([
                'error'=> 'this order not exist',
            ]);
        }

        if(!$item || $item->order_id != $order->id){
            return response()->json([
                'error'=>'this item not exist',
            ]);
        }

        $batch = Batch::find($item->batch_id);

        return response()->json([
            'batch'=>$batch,     
        ],200);
    }

    //reject order
    public function reject_order($order_id, Request $request){
        $company = Auth::guard('company_api')->user();
        $order = Order::find($order_id);

        if(!$order || $order->company_id != $company->id){
            return response()->json([
                'error'=> 'this order not exist',
            ]);
        }

        if($order->is_accept == 0 || $order->is_accept != null){
            return response()->json([
                'message'=>'already rejected',
            ]);
        }

        if($order->is_accept == 1 || $order->is_accept != null){
            return response()->json([
                'message'=>'already accessed',
            ]);
        }

        $mes_company = $request->input('mes_company');

        $order->update([
            'is_accept'=>0,
            'mes_company'=>$mes_company,
        ]);

        return response()->json([
            'message'=>'rejectd successfully',
        ],200);
    }

    //accept order
    public function accept_order($order_id, Request $request){
        $company = Auth::guard('company_api')->user();
        $order = Order::find($order_id);
        $pharmacy = Pharmacy::find($order->pharmacy_id);

        $supplier_id = $request->input('supplier_id');
        $mes_company = $request->input('mes_company');
        $send_date = $request->input('send_date');

        if($send_date <= date('Y-m-d')){
            return response()->json([
                'error'=>'this date not aviable',
            ]);
        }

        if(!$supplier_id){
            return response()->json([
                'error'=>'you should select supplier',
            ]);
        }

        if(!$send_date){
            return response()->json([
                'message'=>'you should add send date',
            ]);
        }

        if(!$order || $order->company_id != $company->id){
            return response()->json([
                'error'=> 'this order not exist',
            ]);
        }

        if($order->is_accept == 1 && $order->is_accept !=  null){
            return response()->json([
                'message'=>'already accessed',
            ]);
        }

        if($order->is_accept == 0 && $order->is_accept !=  null){
            return response()->json([
                'message'=>'already rejected',
            ]);
        }

        $items = $order->order_item;

        foreach($items as $item){
            $batch = Batch::find($item->batch_id);
            $material = Material::find($item->material_id);
            if($item->quantity > $batch->quantity){
                return response()->json([
                    'message'=>'not enought materials in this batch',
                    'item'=>$material,
                ]);
            }
        }

        foreach($items as $item){
            $batch = Batch::find($item->batch_id);
            $material = Material::find($item->material_id);
            $content = Content::where(['pharmacy_id'=>$pharmacy->id,'batch_id'=>$item->batch_id,'material_id'=>$item->material_id])->first();

            $batch->update([
                'quantity'=>$batch->quantity-$item->quantity,
            ]);

            if($send_date <= date('Y,m,d')){
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
        $mes_company = $request->input('mes_company');

        // $pharmacy->update([
        //     'pharmacy_wallet'=>$pharmacy->pharmacy_wallet-$order->order_total,
        // ]);

        $order->update([
            'is_accept'=>1,
            'mes_company'=>$mes_company,
            'send_date'=>$send_date,
        ]);

        return response()->json([
            'success'=>'acccepted order successfully',
        ],200);
    }

    //active batch
    public function batch_activate_disactive($batch_id){
        $company =  Auth::guard('company_api')->user();
        $batch = Batch::find($batch_id);
        $material = Material::find($batch->material_id);

        if(!$batch || $company->id != $material->company_id){
            return response()->json([
                'error'=>'this batch not exist',
            ]);
        }

        if($batch->is_active == 1){
            $batch->update([
                'is_active'=>0,
            ]);
            return response()->json([
                'success'=>'this batch disactivated',
            ]);
        }

        $batch->update([
            'is_active'=>1,
        ]);

        return response()->json([
            'success'=>'activated successfully',     
        ],200);
    }

    //disactive batch
    public function batch_disactivate($batch_id){
        $company =  Auth::guard('company_api')->user();
        $batch = Batch::find($batch_id);
        $material = Material::find($batch->material_id);

        if(!$batch || $company->id != $material->company_id){
            return response()->json([
                'error'=>'this batch not exist',
            ]);
        }

        if($batch->is_active == 0){
            return response()->json([
                'message'=>'this batch is already disactivated',
            ]);
        }

        $batch->update([
            'is_active'=>0,
        ]);

        return response()->json([
            'success'=>'disactivated successfully',     
        ],200);
    }
    
    //get active material information
    public function get_material_information_active($material_id){
        $material = Material::find($material_id);
        $batchs = $material->batch;
        
        if(!$material || $material->is_active == 0){
            return response()->json([
                'error'=>'this material not exist',
            ]);
        }

        return response()->json([
            'material'=>$material,
            'diseases'=>$material->disease,
            'components'=>$material->component,
            'batchs'=>$batchs,
        ]);
    }

    //get disactive material iformation
    public function get_material_information_disactive($material_id){
        $material = Material::find($material_id);
        
        if(!$material || $material->is_active == 1){
            return response()->json([
                'error'=>'this material not exist',
            ]);
        }

        return response()->json([
            'material'=>$material,
            'diseases_sent'=>$material->disease,
            'components_sent'=>$material->component,
            'component_info'=>$material->component_info,
        ]);
    }

    //search supplier by name
    public function search_supplier(Request $request){
        $company = Auth::guard('company_api')->user();

        $supplier_name = $request->input('supplier_name');
        $suppliers = Supplier::where('company_id',$company->id)->where('supplier_name','like','%'.$supplier_name.'%')->get();

        return response()->json([
            'suppliers'=>$suppliers,
        ]);
    }

    //get active materials
    public function get_active_materials(){
        $company = Auth::guard('company_api')->user();
        $materials = $company->material->where('is_active',1);

        return response()->json([
            'materials'=>$materials,
        ]);
    }

    //get disactive materials
    public function get_disactive_materials(){
        $company = Auth::guard('company_api')->user();
        $materials = $company->material->where('is_active',0);

        return response()->json([
            'materials'=>$materials,
        ]);
    }

    //get material information
    public function get_material_information($material_id){
        $material = Material::find($material_id);

        return response()->json([
            'material'=>$material,
            'disease'=>$material->disease,
            'component'=>$material->component,
            'component information'=>$material->component_info,
            'batchs'=>$material->batch,
        ]);
    }

    //get material batch information
    public function get_material_batch_information($material_id,$batch_id){
        $material = Material::find($material_id);
        $batch = Batch::find($batch_id);

        return response()->json([
            'material'=>$material,
            'batch'=>$batch,
        ]);
    }

    //make report
    public function make_report($type, Request $request){
        $company = Auth::guard('company_api')->user();

        $from = $request->input('from');
        $to = $request->input('to');

        $orders = Order::where('is_accept',1)->where('company_id',$company->id)->where('date','>=',$from)->where('date','<=',$to)->get();
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
            'company_id'=>$company->id,
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