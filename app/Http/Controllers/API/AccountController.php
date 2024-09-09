<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function profile(Request $request){
        $user = Auth::user();
        
        return response(['data'=>$user], 200);
    }
}
