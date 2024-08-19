<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'firstname'=>'required|max:140',
            'lastname'=>'required|max:140',
            'gender'=>'required|max:140',
            'email'=>'required|email|unique:users,email',
            'dateofbirth'=>'required|max:140',
            'password'=>"required|string|confirmed|min:8|max:16|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/"
        ]);

        User::insert([
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'email'=>$request->email,
            'gender'=>$request->gender,
            'dateofbirth'=>$request->dateofbirth,
            'password'=>Hash::make($request->password),
            'updated_at'=>Carbon::now(),
            'created_at'=>Carbon::now()
        ]);

        $response = [
            "data"=>'Registration Successful',
        ];

        return response($response, 201);
    }

    public function login(Request $request){
        $data = $request->validate([
            "email"=> 'required|string|max:191',
            "password"=> "required|string",
        ]);

        $user = User::where('email', $data['email'])->first();

        if(!$user || !Hash::check($data['password'], $user->password) ){
            return response(["message"=>"Invalid credentials","status"=>0], 422);
        }
        // elseif($user->email_verified_at == null){
        //     return response(["message"=>"Please verify your email or reset your password","status"=>0], 422);
        // }
        else{
            //create token
            $token = $user->createToken('chatapp')->accessToken;

            $response = [
                'data' => $user,
                'token' => $token
            ];

            return response($response, 200);
        }
    }

    public function updatepassword(Request $request){
        $request->validate([
            'password'=>"required|string|confirmed|min:8|max:16|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/"
        ]);

        User::where('id', Auth::user()->id)->update([
            'password'=>$request->password,
        ]);

        $response = [
            "data"=>'password updated',
        ];

        return response($response, 201);
    }

    public function logout(){
        auth()->user()->token()->delete();
        return response(["message"=>"Logged out successfully", 'status'=>100]);
    }

}