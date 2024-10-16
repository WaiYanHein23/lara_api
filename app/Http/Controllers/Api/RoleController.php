<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    //role created
    public function store(){
        $validator=Validator::make(request()->all(),[
            'name'=>'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'unprocessable',
                'error'=>$validator->errors()
            ],422);
        }

        $role=Role::create([
            'name'=>request('name')
        ]);
         return response()->json([
            'message'=>'Create Successful'
         ]);

    }

    //role delete
    public function delete(Role $role){
        $role->delete();
        return response()->json([
            'message'=>"delete success"
        ]);
    }

}
