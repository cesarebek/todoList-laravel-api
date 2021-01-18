<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //User SignUp
    public function signup(Request $request){
        //All inputs validation will be hadled in front-end...
        $inputs = $request->all();
        //Hashing password
        $inputs["password"] = Hash::make($request->password);
        //Creating New User
        $user = User::create($inputs);
        //Response
        return response()->json([
            "message"=>"User created successfuly",
            "data"=>$user
        ]);
    }

    //User SignIn
    public function signin(Request $request){
        //Searching for the user in db
        $user = User::where('email', $request->email)->first();
        //Check
        if(is_null($user) || Hash::check($request->password, $user->password)){
            return response()->json([
                'message'=>'Email or Password not valid!'
            ], 404);
        }
        //Adding user Token
        $token = $user->createToken('bek_cezary')->plainTextToken;
        //Response
        return response()->json([
            "message"=>"User logged successfuly!",
            "token"=>$token,
            "data"=>$user
        ]);
    }

    //Retrieving User details
    public function user(){
        $user = Auth::user();
        if(!is_null($user)) { 
            return response()->json(['data' => $user]);
        }

        return response()->json(['message' => 'Please log-in']);   
    }
}
