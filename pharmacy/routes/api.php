<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pharmacy\ClientController;
use App\Http\Controllers\Pharmacy\CompanyController;
use App\Http\Controllers\Pharmacy\DoctorController;
use App\Http\Controllers\Pharmacy\EmployeeController;
use App\Http\Controllers\Pharmacy\GetsController;
use App\Http\Controllers\Pharmacy\PharmacyController;
use App\Http\Controllers\Pharmacy\SearchController;
use App\Http\Controllers\TestController;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function(){
    Route::post('login','login');
    Route::post('register','register');
});

Route::post('dash',[DashboardController::class,'invoked'])->middleware();



Route::group(['prefix'=>'pharmacy'],function(){
    Route::post('/register',[PharmacyController::class,'register']);
    Route::post('/login',[PharmacyController::class,'login']);

    Route::group(['prefix'=>'dashboard','middleware'=>'pharmacy_token'],function(){
        Route::post('/logout',[PharmacyController::class,'logout']);
        Route::get('/',[PharmacyController::class,'dashboardInvoked']);
        Route::get('/employee',[PharmacyController::class,'get_employees']);
        Route::get('/employee/{employee_id}',[PharmacyController::class,'get_employee_information']);
        Route::post('/details',[PharmacyController::class,'set_details']);
        Route::get('/get_details',[PharmacyController::class,'get_details']);
        Route::post('/add_employee',[PharmacyController::class,'add_employee']);
        Route::post('/remove/{employee_id}',[PharmacyController::class,'remove']);
        Route::post('/edit_employee/{employee_id}',[PharmacyController::class,'edit_employee']);
        Route::post('/give_salary/{employee_id}',[PharmacyController::class,'give_salary']);
        Route::post('/edit_profile',[PharmacyController::class,'edit_profile']);
        Route::post('/edit_details/{detail_id}',[PharmacyController::class,'edit_details']);
        Route::post('/remove_details/{detail_id}',[PharmacyController::class,'remove_details']);
        Route::post('/create_order',[PharmacyController::class,'create_order']);
        Route::post('/add_order_item/{order_id}',[PharmacyController::class,'add_order_item']);
        Route::post('/send_order/{order_id}',[PharmacyController::class,'send_order']);
        Route::get('/get_order',[PharmacyController::class,'get_order']);
        Route::get('/get_order/{order_id}',[PharmacyController::class,'get_order_details']);
        Route::get('/get_order/{order_id}/{material_id}',[PharmacyController::class,'get_order_item_information']);
        Route::get('/get_item_batch_details/{order_id}/{item_id}',[PharmacyController::class,'get_item_batch_details']);
        Route::post('/add_content',[PharmacyController::class,'add_content']);
        Route::post('/edit_content/{content_id}',[PharmacyController::class,'edit_content']);
        Route::get('/minimum_notification',[PharmacyController::class,'minimum_notification']);
        Route::get('/maximum_notification',[PharmacyController::class,'maximum_notification']);
        Route::get('/get_require/{employee_id}',[PharmacyController::class,'get_require']);
        Route::post('/reject_require/{require_id}',[PharmacyController::class,'reject_require']);
        Route::post('/accept_require/{require_id}',[PharmacyController::class,'accept_require']);
        Route::post('/create_bill',[PharmacyController::class,'create_bill']);
        Route::post('/add_bill_item/{bill_id}',[PharmacyController::class,'add_bill_item']);
        Route::get('/get_bills',[PharmacyController::class,'get_bills']);
        Route::get('/get_bills/{bill_id}',[PharmacyController::class,'get_bill_items']);
        Route::get('/get_bills/{bill_id}/{item_id}',[PharmacyController::class,'get_bill_content']);
        Route::post('/return_bill/{bill_id}',[PharmacyController::class,'return_bill']);
        Route::get('/get_orders',[PharmacyController::class,'get_orders']);
        Route::get('/get_order_items/{order_id}',[PharmacyController::class,'get_order_items']);
        Route::post('/accept_client_order/{order_id}',[PharmacyController::class,'accept_client_order']);
        Route::post('/reject_client_order/{order_id}',[PharmacyController::class,'reject_client_order']);
        Route::post('/create_stock',[PharmacyController::class,'create_stock']);
        Route::get('/get_stocks',[PharmacyController::class,'get_stocks']);
        Route::get('/get_stocks/{stock_id}',[PharmacyController::class,'get_stock_items']);
        Route::get('/get_stocks/{stock_id}/{material_id}',[PharmacyController::class,'get_stock_item_information']);
        Route::post('/add_stock_item/{stock_id}',[PharmacyController::class,'add_stock_item']);
        Route::post('/return_stock_item/{stock_id}',[PharmacyController::class,'return_stock_item']);
        Route::get('/get_waste_batchs',[PharmacyController::class,'get_waste_batchs']);
        Route::get('/get_waste_batch_content/{batch_id}',[PharmacyController::class,'get_waste_batch_content']);
        Route::post('/create_waste',[PharmacyController::class,'create_waste']);
        Route::post('/add_waste_item/{waste_id}',[PharmacyController::class,'add_waste_item']);
        Route::get('/get_companies',[PharmacyController::class,'get_companies']);
        Route::get('/get_material_information/{material_id}',[PharmacyController::class,'get_material_information']);
        Route::post('/send_message/{employee_id}',[PharmacyController::class,'send_message']);
        Route::post('/get_messages/{employee_id}',[PharmacyController::class,'get_messages']);
        Route::get('get_contents',[PharmacyController::class,'get_contents']);
        Route::get('/get_comapny_batchs/{material_id}',[PharmacyController::class,'get_company_batchs']);
        Route::get('/get_content_info',[PharmacyController::class,'get_content_info']);
        Route::get('/get_content_detail/{material_id}',[PharmacyController::class,'get_content_detail']);
        Route::get('/get_requires',[PharmacyController::class,'get_requires']);
        Route::get('/get_requires/{require_id}',[PharmacyController::class,'get_require_information']);
        Route::get('/search_employee',[PharmacyController::class,'search_employee']);
        Route::post('/remove_stock/{stock_id}',[PharmacyController::class,'remove_stock']);
        Route::get('/get_all_materials',[PharmacyController::class,'get_all_materials']);
        Route::post('/check_orders',[PharmacyController::class,'check_orders']);
        Route::get('/get_wastes',[PharmacyController::class,'get_wastes']);
        Route::get('/get_wastes/{waste_id}',[PharmacyController::class,'get_waste_information']);
        Route::get('/get_wastes/{waste_id}/{item_id}',[PharmacyController::class,'get_waste_item_information']);
        Route::get('/get_all_pharmacy_materials',[PharmacyController::class,'get_all_pharmacy_materials']);
        Route::get('/get_all_pharmacy_materials/{material_id}',[PharmacyController::class,'get_material_details']);
        Route::get('get_minimum',[PharmacyController::class,'get_minimum']);
        Route::get('get_maximum',[PharmacyController::class,'get_maximum']);
        Route::get('get_rates',[PharmacyController::class,'get_rates']);
        Route::post('add_remove_favorite/{material_id}',[PharmacyController::class,'add_remove_favorite']);
        Route::get('get_favorite',[PharmacyController::class,'get_favorite']);
        Route::post('make_report/{type}',[PharmacyController::class,'make_report']);
        Route::post('add_favorite',[PharmacyController::class,'add_favorite']);
        Route::post('delete_favorite/{favorite_id}',[PharmacyController::class,'delete_favorite']);
    });
});

Route::group(['prefix'=>'employee'],function(){
    Route::post('/login',[EmployeeController::class,'login']);

    Route::group(['prefix'=>'dashboard','middleware'=>'employee_token'],function(){
        Route::get('/',[EmployeeController::class,'dashboardInvoked']);
        Route::post('/logout',[EmployeeController::class,'logout']);
        Route::post('/add_require',[EmployeeController::class,'add_require']);
        Route::get('/get_require',[EmployeeController::class,'get_require']);
        Route::get('/get_require/{require_id}',[EmployeeController::class,'get_require_info']);
        Route::post('/create_bill',[EmployeeController::class,'create_bill']);
        Route::post('/add_bill_item/{bill_id}',[EmployeeController::class,'add_bill_item']);
        Route::get('/get_bills',[EmployeeController::class,'get_bills']);
        Route::get('/get_bills/{bill_id}',[EmployeeController::class,'get_bill_items']);
        Route::get('/get_bills/{bill_id}/{item_id}',[EmployeeController::class,'get_bill_content']);
        Route::post('/return_bill/{bill_id}',[EmployeeController::class,'return_bill']);
        Route::get('/get_orders',[EmployeeController::class,'get_orders']);
        Route::get('/get_order_items/{order_id}',[EmployeeController::class,'get_order_items']);
        Route::post('/accept_client_order/{order_id}',[EmployeeController::class,'accept_client_order']);
        Route::post('/reject_client_order/{order_id}',[EmployeeController::class,'reject_client_order']);
        Route::get('/get_waste_batchs',[EmployeeController::class,'get_waste_batchs']);
        Route::get('/get_waste_batch_content/{batch_id}',[EmployeeController::class,'get_waste_batch_content']);
        Route::post('/create_waste',[EmployeeController::class,'create_waste']);
        Route::post('/add_waste_item/{waste_id}',[EmployeeController::class,'add_waste_item']);
        Route::post('/send_message',[EmployeeController::class,'send_message']);
        Route::post('/get_messages',[EmployeeController::class,'get_messages']);
        Route::post('make_report/{type}',[EmployeeController::class,'make_report']);
    });
});

Route::group(['prefix'=>'company'],function(){
    Route::post('/register',[CompanyController::class,'register']);
    Route::post('/login',[CompanyController::class,'login']);

    Route::group(['prefix'=>'dashboard','middleware'=>'company_token'],function(){
        Route::post('/logout',[CompanyController::class,'logout']);
        Route::get('/',[CompanyController::class,'dashboardInvoked']);
        Route::post('/add_material',[CompanyController::class,'add_material']);
        Route::get('/get_materials',[CompanyController::class,'get_materials']);
        Route::post('/add_component/{material_id}',[CompanyController::class,'add_component']);
        Route::post('/add_disease/{material_id}',[CompanyController::class,'add_disease']);
        Route::post('/edit_material/{material_id}',[CompanyController::class,'edit_material']);
        Route::post('/edit_material_disease/{material_id}/{disease_id}',[CompanyController::class,'edit_material_disease']);
        Route::post('/edit_company',[CompanyController::class,'edit_company']);
        Route::post('/add_supplier',[CompanyController::class,'add_supplier']);
        Route::post('/edit_supplier/{supplier_id}',[CompanyController::class,'edit_supplier']);
        Route::post('/remove_supplier/{supplier_id}',[CompanyController::class,'remove_supplier']);
        Route::post('/add_batch/{material_id}',[CompanyController::class,'add_batch']);
        Route::get('/get_batchs/{material_id}',[CompanyController::class,'get_batch']);
        Route::get('/get_supplier',[CompanyController::class,'get_supplier']);
        Route::get('/get_supplier/{supplier_id}',[CompanyController::class,'get_supplier_information']);
        Route::get('/get_order',[CompanyController::class,'get_order']);
        Route::get('/get_order_item/{order_id}',[CompanyController::class,'get_order_item']);
        Route::get('/get_item_batch_details/{order_id}/{item_id}',[CompanyController::class,'get_item_batch_details']);
        Route::post('/reject_order/{order_id}',[CompanyController::class,'reject_order']);
        Route::post('/accept_order/{order_id}',[CompanyController::class,'accept_order']);
        Route::post('/batch_activate_disactive/{batch_id}',[CompanyController::class,'batch_activate_disactive']);
        Route::get('get_material/{material_id}',[CompanyController::class,'get_material']);
        Route::get('/get_active_materials/{material_id}',[CompanyController::class,'get_material_information_active']);
        Route::get('/get_disactive_materials/{material_id}',[CompanyController::class,'get_material_information_disactive']);
        Route::get('/search_supplier',[CompanyController::class,'search_supplier']);
        Route::get('/get_active_materials',[CompanyController::class,'get_active_materials']);
        Route::get('/get_disactive_materials',[CompanyController::class,'get_disactive_materials']);
        Route::get('/get_material_information/{material_id}',[CompanyController::class,'get_material_information']);
        Route::get('/get_material_information/{material_id}/{batch_id}',[CompanyController::class,'get_material_batch_information']);
        Route::post('make_report',[CompanyController::class,'make_report']);
    });
});

Route::group(['prefix'=>'search'],function(){
    Route::get('/pharmacy_name',[SearchController::class,'pharmacy_name']);
    Route::get('/company_name',[SearchController::class,'company_name']);
    Route::get('/material_name',[SearchController::class,'material_name']);
    Route::get('/material_type',[SearchController::class,'material_type']);
    Route::get('/material_component',[SearchController::class,'material_component']);
    Route::get('/material_disease',[SearchController::class,'material_disease']);
    Route::get('/material_scientific_name',[SearchController::class,'material_scientific_name']);
    Route::get('/duty_pharmacies',[SearchController::class,'duty_pharmacies']);
});

Route::group(['prefix'=>'client'],function(){
    Route::post('/register',[ClientController::class,'register']);
    Route::post('/login',[ClientController::class,'login']);

    Route::group(['prefix'=>'dashboard','middleware'=>'client_token'],function(){
        Route::get('/',[ClientController::class,'dashboard']);
        Route::post('/logout',[ClientController::class,'logout']);
        Route::post('/create_order/{pharmacy_id}',[ClientController::class,'create_order']);
        Route::post('/add_order_item/{order_id}',[ClientController::class,'add_order_item']);
        Route::get('/get_orders',[ClientController::class,'get_orders']);
        Route::get('/get_order_items/{order_id}',[ClientController::class,'get_order_items']);
        Route::get('/get_bills',[ClientController::class,'get_bills']);
        Route::get('/get_bill_items/{bill_id}',[ClientController::class,'get_bill_items']);
        Route::post('/edit_client',[ClientController::class,'edit_client']);
        Route::post('/rating/{pharmacy_id}',[ClientController::class,'rating']);
        Route::post('make_report/{type}',[ClientController::class,'make_report']);
    });
});

Route::group(['prefix'=>'doctor'],function(){
    Route::post('/register',[DoctorController::class,'register']);
    Route::post('/login',[DoctorController::class,'login']);

    Route::group(['prefix'=>'dashboard','middleware'=>'doctor_token'],function(){
        Route::get('/',[DoctorController::class,'dashboard']);
        Route::post('/logout',[DoctorController::class,'logout']);
        Route::post('/edit_doctor',[DoctorController::class,'edit_doctor']);
        Route::post('/create_order/{pharmacy_id}',[DoctorController::class,'create_order']);
        Route::post('/add_order_item/{order_id}',[DoctorController::class,'add_order_item']);
        Route::get('/get_clients',[DoctorController::class,'get_clients']);
        Route::get('/get_clients/{client_id}',[DoctorController::class,'get_client_information']);
        Route::post('make_report',[DoctorController::class,'make_report']); 
    });
});

Route::group(['prefix'=>'gets'],function(){
    Route::get('/get_types',[GetsController::class,'get_types']);
    Route::get('/get_companies',[GetsController::class,'get_companies']);
    Route::get('/get_components',[GetsController::class,'get_components']);
    Route::get('/get_diseases',[GetsController::class,'get_diseases']);
    Route::get('/get_material_information/{material_id}',[GetsController::class,'get_material_information_active']);
    Route::get('/get_material_batchs/{material_id}',[GetsController::class,'get_material_batchs_active']);
    Route::get('/get_company_materials/{company_id}',[GetsController::class,'get_company_materials']);
    Route::get('/get_states',[GetsController::class,'get_states']);
    Route::get('/get_state_locations/{state_id}',[GetsController::class,'get_state_locations']);
    Route::get('/get_delivery_companies',[GetsController::class,'get_delivery_companies']);
    Route::get('/get_delivery_companies/{delivery_id}',[GetsController::class,'get_delivery_company_information']);
    Route::get('/get_clients',[GetsController::class,'get_clients']);
    Route::get('/get_clients/{client_id}',[GetsController::class,'get_client_information']);
    Route::get('/get_doctors',[GetsController::class,'get_doctors']);
});