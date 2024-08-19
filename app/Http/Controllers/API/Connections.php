<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Connections extends Controller
{
    public function viewallpeople(Request $request){
        $user = User::where('usertype','user')->
        where('id','!=',Auth::user()->id)
        ->paginate($request->per_page);
        return response(['data'=>$user], 200);
    }
    
    public function viewsingleprofile(Request $request, $id){
        $user = User::where('usertype','user')->
        where('id','=',$id)
        ->where('id','!=',Auth::user()->id)
        ->first();
        return response(['data'=>$user], 200);
    }
}
