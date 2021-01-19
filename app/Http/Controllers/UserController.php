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
        $inputs['password'] = Hash::make($request->password);
        //Creating New User
        $user = User::create($inputs);
        //Adding token for instant login
        $token = $user->createToken('bek_cezary')->plainTextToken;
        //Response
        return response()->json([
            "message"=>"User created successfuly",
            "data"=>$user,
            'token'=>$token
        ]);
    }

    //User SignIn
    public function signin(Request $request){
        //Searching for the user in db
        $user = User::where('email', $request->email)->first();
        //Check
        if(is_null($user) || !Hash::check($request->password, $user->password)){
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

    //User Update
    public function update(Request $req){
        $user = $req->user()->update($req->all());
        //Response
         return response()->json([
            "message"=>"User updated successfuly!",
            "data"=>$user
        ]);
    }

    //User SignOut
    public function logout(Request $req){
        $req->user()->currentAccessToken()->delete();
        //Response
        return response()->json([
            'message'=>'User logged out successfuly'
        ]);
    }

    //User Delete
    public function destroy(Request $req){
        $req->user()->delete();
        //Response
        return response()->json([
            'message'=>'User deleted successfuly'
        ]);
    }

    //Retrieving User details
    public function user(){
        return response()->json(['data'=>Auth::user()]);
    }    
}
