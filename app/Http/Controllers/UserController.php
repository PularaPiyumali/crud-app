<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\EmailDomainRule;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
    public function welcome(){
        return 'Hi Welcome User';
    }

    public function getUsers(){
        $users = User::paginate(5);

        return response()->json([
            'status' => 200,
            'users' => $users
        ], 200);
    }

    public function registeration(Request $request){

        $validatedData = $request->validate([
            'name'=>['required','min:3','max:10'],
            'email'=>['required','email'],
            'password'=>['required','min:8','max:100']
        ]);

        
        $name = $validatedData['name'];

        $data = [
            'status'=>200,
            'message' => "User $name successfully registered",
            'user'=>$validatedData
            ];
        
        return response()->json($data,200);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'max:10'],
            'email' => ['required', 'email','unique:users,email', new EmailDomainRule()],
            'password' => ['required', 'min:8', 'max:200']
        ],[
            'name.required' => 'Please fill the name field',
            'email.unique' => 'This email is already registered',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);
        
        User::create($validatedData);

        return response()->json([
            'status' => 200,
            'message' => "User successfully registered",
        ], 200);
    }
    
}
