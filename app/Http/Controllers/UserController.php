<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $userID = User::insertGetId([
            'name' => strtolower($request->name),
            'email' => strtolower($request->email),
            'password' => bcrypt($request->password)
        ]);

        $roleID = Role::where("name", strtolower($request->role))->pluck('id')->toArray();

        // Use the roles() method on the User model to access the relationship
        User::find($userID)->roles()->attach($roleID);

        return response()->json(['response' => ['msg' => "User is created", 'name' => $request->name, 'role' => $request->role]]);
    }
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $response['token'] =  $user->createToken('access_management')->plainTextToken;
            $response['name'] =  $user->name;
            $response['role'] =  User::with('roles')->where('id', $user->id)->get();

            return response()->json(['response' => $response, 'User login successfully.']);
        }
        else{
            return response()->json(['error'=>'Unauthorised']);
        }
    }

}
