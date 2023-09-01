<?php

namespace App\Http\Controllers;

use App\Models\CompanyModel;
use App\Models\MaterialModel;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function get_material($id){
        $company = CompanyModel::find($id);
        $materials = $company->materials;
        return response()->json([
            'message'=> $materials,
        ]);
    }

    public function get_company($id){
        $material = MaterialModel::find($id);
        $company = $material->company;
        return response()->json([
            'message'=> $company
        ]);
    }

    public function get_installation($id){
        $material = MaterialModel::find($id);
        $insallations = $material->installations;;
        return response()->json([
            'message'=> $insallations
        ]);
    }
}
