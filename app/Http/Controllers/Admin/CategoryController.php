<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //create categories
    public function store(){
        $validator=Validator::make(request()->all(),[
            'name'=>['required']
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>"unprocessable",
                'error'=>$validator->errors()
            ],422);
        }

        $categories=Categories::create([
            'name'=>request('name')
        ]);

        return response()->json([
            'message'=>'Create Successful',
            'category'=>$categories
           
        ]);

    }

    
        //update categories
        public function update(Categories $category){
            $validator=Validator::make(request()->all(),[
                'name'=>['required']
            ]);
            $category->update([
                'name'=>request('name')
            ]);

            return response()->json([
                'message'=>'Update Successful',
                'category'=>$category
               
            ]);
        }

        //delete category
        public function delete(Categories $category){
            $category->delete();
            return response()->json([
                'message'=>"Delete Successful"
            ]);

        }

        //get all categories
        public function show(){
            $categories=Categories::all();
            return response()->json([
                'categories'=>$categories
            ]);
        }
}
