<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Company;
use App\Models\Component;
use App\Models\Detail;
use App\Models\Disease;
use App\Models\Material;
use App\Models\Pharmacy;
use App\Models\Type;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //search pharmacies by name
    public function pharmacy_name(SearchRequest $request){
        $pharmacy_name = $request->input('pharmacy_name');

        $pharmacies = Pharmacy::where('pharmacy_name','like','%'.$pharmacy_name.'%')->where('is_active',1)->select()->get();

        if($pharmacies == '[]'){
            return response()->json([
                'message'=>'this pharmacy not exists',
            ]);
        }
        return response()->json([
            'pharmacies'=>$pharmacies,
        ],200);
    }

    //search companies by name
    public function company_name(SearchRequest $request){
        $company_name = $request->input('company_name');

        $companies = Company::where('company_name','like','%'.$company_name.'%')->where('is_active',1)->select()->get();

        if($companies == '[]'){
            return response()->json([
                'message'=>'this company not exists',
            ]);
        }
        return response()->json([
            'companies'=>$companies,
        ],200);
    }

    //search materials by name
    public function material_name(SearchRequest $request){
        $material_name = $request->input('material_name');

        $materials = Material::where('material_name','like','%'.$material_name.'%')->where('is_active',1)->select()->get();

        if($materials == '[]'){
            return response()->json([
                'message'=>'this material not exists',
            ]);
        }
        return response()->json([
            'materials'=>$materials,
        ]);
    }

    //search materials by scientific name
    public function material_scientific_name(SearchRequest $request){
        $scientific_name = $request->input('scientific_name');

        $materials = Material::where('scientific_name','like','%'.$scientific_name.'%')->where('is_active',1)->select()->get();

        if($materials == '[]'){
            return response()->json([
                'message'=>'this material not exists',
            ]);
        }
        return response()->json([
            'materials'=>$materials,
        ]);
    }

    //search materials by type
    public function material_type(SearchRequest $request){
        $material_type = $request->input('material_type');

        $materials = Material::where(['type_id'=>$material_type,'is_active'=>1])->select()->get();

        if($materials == '[]'){
            return response()->json([
                'message'=>'this material not exists',
            ]);
        }

        return response()->json([
            'materials'=>$materials,
        ],200);
    }

    //search material by components
    public function material_component(SearchRequest $request){
        $material_component = $request->input('material_component');
        
        $component = Component::where('component_name','like','%'.$material_component.'%')->first();

        if(!$component){
            return response()->json([
                'message'=>'this component not exists',
            ]);
        }
        $materials = $component->material;

        if($materials == '[]'){
            return response()->json([
                'message'=>'this material not exists',
            ]);
        }

        return response()->json([
            'materials'=>$materials,
        ],200);
    }

    //search materials by disease
    public function material_disease(SearchRequest $request){
        $material_disease = $request->input('material_disease');

        $disease = Disease::where('disease_name','like','%'.$material_disease.'%')->where('is_active',1)->first();

        if(!$disease){
            return response()->json([
                'message'=>'this disease not exist',  
            ]);
        }

        $medicines = $disease->medicine;

        if($medicines == '[]'){
            return response()->json([
                'message'=>'this material not exist',    
            ]);
        }

        return response()->json([
            'medicines'=> $medicines,        
        ],200);
    }

    //get duty pharmacies
    public function duty_pharmacies(Request $request){
        $location_id = $request->input('location_id');
        $day = $request->input('day');
        $details = Detail::where(['is_duty'=>1,'day'=>$day,'is_duty'=>1])->get();
        
        $pharmacies=[];

        for($i=0;$i<$details->count();$i++){
            $pharmacies[$i]=Pharmacy::where(['id'=>$details[$i]->pharmacy_id,'location_id'=>$location_id])->first();
        }

        return response()->json([
            'pharmacies'=>$pharmacies,
        ]);
    }
}