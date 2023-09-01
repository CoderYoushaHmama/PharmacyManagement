<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function invoked(){
        $user =  auth()->user();
        return [
            'user'=> auth()->user()
        ];
    }
}
