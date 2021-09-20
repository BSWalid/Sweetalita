<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request){
        $fileds = $request -> validate(
            [
                'name' =>'required | string',
                'email' =>'required | string |unique:users,email',
                'password' =>'required | string ',
                'phone' => 'required'
                                                                ]
            );

            $user = User::create([
                    'name'=>$fileds['name'],
                    'email'=>$fileds['email'],
                    'password'=> Hash::make($fileds['password']),
                    'phone' => $fileds['phone'],

            ]);

            $token = $user->createToken('myapptoken')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response($response,201);




    }

    public function login(Request $request){
        $fileds = $request -> validate(
            [
                'email' =>'required | string',
                'password' =>'required | string ',
                            ]
            );

            $user = User::where('email',$fileds['email'])->first();



            if(!$user || !Hash::check($fileds['password'], $user->password) ){

                return response([
                    'message' => 'Invalid login details',
                ],401);
            }

            $token = $user ->createToken('myapptoken')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response($response,201);

    }

    public function profile(Request $request){

        return $request->user();
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response([
            'message'=>'you are logged out'

        ]);



    }
}
