<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\EmailDomainRule;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Post;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    public function welcome(){
        return 'Hi Welcome User';
    }

    public function getAllUsers(){
        $users = User::paginate(5);  

        return response()->json([
            'status' => 200,
            'users' => $users
        ], 200);
    }
    

    public function register(Request $request){

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

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'max:200'],
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

    public function findUsers(){
        $user = User::find(11);       
        $firstUser = User::first();       
        $findUser = User::where('email', 'pulara@gmail.com')->first(); 
        $user->assignRole('user');
        $user->assignRole('editor', 'user');


        return response()->json([
            'status' => 200,
            'message' => "Successfull",
            'user' => $user
        ], 200);
    }

    public function index (){
           
        $postUser = Post::find(2)->user;
        $userPosts = User::find(2)->posts;

        return [
        'user_of_post_2' => $postUser,
        'posts_of_user_2' => $userPosts
    ];

    }

    public function showCategories() {

    $postCategories = Post::find(3)->categories;

    return [
        'categories_of_post_3' => $postCategories
    ];
    }

    public function update(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'name' => ['min:3', 'max:10'],
            'email' => ['email','unique:users,email', new EmailDomainRule()],
            'password' => ['min:8', 'max:200']
        ],[
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

        if (isset($validatedData['password'])) {
        $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user = User::find($id);
         if (!$user) {
        return response()->json([
            'status' => 404,
            'message' => 'User not found'
        ], 404);
    }
        
        $user->update($validatedData);

        return response()->json([
            'status' => 200,
            'message' => "User successfully updated",
        ], 200);

    }

    public function delete($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'status' => 404,
            'message' => 'User not found'
        ], 404);
    }

    $user->delete();

    return response()->json([
        'status' => 200,
        'message' => 'User successfully deleted'
    ], 200);
}

public function assignUserRolePermission(){

    $user = User::find(1);
    $user->givePermissionTo('create posts');
    $user->givePermissionTo(['edit posts', 'delete posts']);

    $role = Role::findByName('admin');
    $role->givePermissionTo(['create posts', 'edit posts', 'delete posts']);
}

public function hasUserRolePermission(){

    $user = User::find(1);
    $user->hasRole('admin');             
    $user->can('edit posts');            
    $user->hasAnyRole(['editor','user']);
    $user->hasAllPermissions(['edit posts','delete posts']);
}
}
