<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //user register
    public function store(){
      try{
        $validator=Validator::make(request()->all(),
        [
            'name'=>['required','min:4','max:30'],
            'email'=>['required','email'],
            'password'=>['required','min:8','max:12'],
            'address'=>['required','max:30'],
            'phone'=>['required','min:9','max:15'],
            'role_id'=>['nullable']
        ]
        );

        if($validator->fails()){
            return response()->json([
                'message'=>'unprocessable',
                'error'=>$validator->errors()
            ],422);
        }

        $isExits=User::where('email',request('email'))->exists();
        if($isExits){

            return response()->json([
                'message'=>'Email already exists',
                'error'=>$validator->errors()
            ],422);
        }

        $user=User::create([
            'name'=>request('name'),
            'email'=>request('email'),
            'password'=>request('password'),
            'address'=>request('address'),
            'phone'=>request('phone'),
            'role_id'=>request('role_id')?? 0
        ]);

        $token= $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'message'=>'User Create Successful',
            'token'=>$token
        ],201);
      }catch (Exception $e){
        return response()->json([
            'message'=>$e->getMessage()
        ]);
      }
   
    }

//user login
public function login(){
    $validator=Validator::make(request()->all(),
    [
        'email'=>['required','email'],
        'password'=>['required','min:8','max:12'],
    ]
    );

    if($validator->fails()){
        return response()->json([
            'message'=>'unprocessable',
            'error'=>$validator->errors()
        ],422);
    }

    $user=User::where('email',request('email'))->first();

    if(!$user){
        return response()->json([
            'message'=>'Email does not exists'
        ],422);
    }

    $checkPass=Hash::check(request('password'),$user->password);

    if(!$checkPass){
        return response()->json([
            'message'=>'Password is not correct'
        ]);
    }
    
    $token= $user->createToken('user_token')->plainTextToken;

    if($checkPass){
        return response()->json([
            'message'=>"Login Successful",
            'token'=>$token
        ]);
    }
}

public function profileImageUpdate(User $user){

    $validator=Validator::make(request()->all(),
    [
        'profile'=>['required']
    ]
    );

    if($validator->fails()){
        return response()->json([
            'message'=>'unprocessable',
            'error'=>$validator->errors()
        ],422);
    }

// if($user->id != request()->user()->id){
// return response()->json([
// 'message'=>"You can not update another user"
// ],422);
// }
    
 $path=request('profile')->store('public');
$url= Storage::url($path);

$user->update([
    'profile'=>$url
]);

return response()->json([
    'message'=>"Update Successful"
]);

}
}
