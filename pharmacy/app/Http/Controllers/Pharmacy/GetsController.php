<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Cdelivery;
use App\Models\Client;
use App\Models\Company;
use App\Models\Component;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Material;
use App\Models\State;
use App\Models\Type;
use Illuminate\Http\Request;

class GetsController extends Controller
{
    //get all types
    public function get_types(){
        $types = Type::get();

        return response()->json([
            'types'=>$types,
        ]);
    }

    //get all components
    public function get_components(){
        $components = Component::get();

        return response()->json([
            'components'=>$components,
        ]);
    }

    //get all diseases
    public function get_diseases(){
        $diseases = Disease::get();

        return response()->json([
            'diseases'=>$diseases,
        ]);
    }

    //get material information
    public function get_material_information_active($material_id){
        $material = Material::find($material_id);
        
        if(!$material || $material->is_active == 0){
            return response()->json([
                'error'=>'this material not exist',
            ]);
        }

        return response()->json([
            'material'=>$material,
            'diseases'=>$material->disease,
            'components'=>$material->component,
        ]);
    }

    //get company materials
    public function get_company_materials($company_id){
        $company = Company::find($company_id);

        if(!$company){
            return response()->json([
                'error'=>'this company not exist',
            ]);
        }

        return response()->json([
            'materials'=>$company->material->where('is_active',1),     
        ]);
    }

    //get all states
    public function get_states(){
        $states = State::get();

        return response()->json([
            'states'=>$states,     
        ]);
    }


    //get state locations
    public function get_state_locations($state_id){
        $state = State::find($state_id);

        if(!$state){
            return response()->json([
                'error'=>'this state not exist',
            ]);
        }

        return response()->json([
            'location'=>$state->location,     
        ]);
    }

    //get delivery companies
    public function get_delivery_companies(){
        $cdeliveries = Cdelivery::get();

        return response()->json([
            'delivery_companies'=>$cdeliveries,
        ]);
    }

    //get delivery company information
    public function get_delivery_company_information($delivery_id){
        $cdelivery = Cdelivery::find($delivery_id);

        return response()->json([
            'company_delivery_information'=>$cdelivery,
        ]);
    }

    //get all companies
    public function get_companies(){
        $companies = Company::get();
        return response()->json([
            'companies'=>$companies,
        ]);
    }

    //get batchs of material
    public function get_material_batchs_active($material_id){
        // $material = Material::find($material_id);
        $batchs = Batch::where(['material_id'=>$material_id,'is_active'=>1])->get();
        return response()->json([
            'batchs'=>$batchs,
        ]);
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

    //get all doctors
    public function get_doctors(){
        $doctors = Doctor::get();
        return response()->json([
            'doctors'=>$doctors,
        ]);
    }
}