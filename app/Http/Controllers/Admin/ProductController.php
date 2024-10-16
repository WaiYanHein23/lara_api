<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //create products
    public function store(){
        $validator=Validator::make(request()->all(),[
            'name'=>['required'],
            'description'=>['nullable'],
            'quantity'=>['required'],
            'price'=>['required'],
            'category_id'=>['required']
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>"unprocessable",
                'error'=>$validator->errors()
            ],422);
        }

        $products=Product::create([
            'name'=>request('name'),
            'description'=>request('description'),
            'quantity'=>request('quantity'),
            'price'=>request('price'),
            'category_id'=>request('category_id')
        ]);
        return response()->json([
            'message'=>"Create Successful",
            'products'=>$products
        ]);
    }

     //update products
     public function update(Product $product){
        $validator=Validator::make(request()->all(),[
            'name'=>['required'],
            'description'=>request('description'),
            'quantity'=>request('quantity'),
            'price'=>request('price'),
            'category_id'=>request('category_id')
        ]);
        $product->update([
            'name'=>request('name')
        ]);

        return response()->json([
            'message'=>'Update Successful',
            'product'=>$product
           
        ]);
    }

    //delete products
    public function delete(Product $product){
        $product->delete();
        return response()->json([
            'message'=>"Delete Successful"
        ]);

    }


     //get all products
     public function show(){
        $products=Product::with('category','images')->get();
        return response()->json([
            'products'=>$products
        ]);
    }

    //product details
    public function detail(Product $product){
        $product=Product::where('id',$product->id)->with('category','images')->first();
        return response()->json([
            'product'=>$product
        ]);
    }

    
    //updateImage
    public function updateImage(Product $product){
        $validator=Validator::make(request()->all(),[
            'images'=>['required']
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>"unprocessable",
                'error'=>$validator->errors()
            ],422);
        }

        $uploadFile=request('images');
        $imagesUrl=[];
        if(gettype($uploadFile) == 'array'){
            foreach($uploadFile as $file){

                if(gettype($file) == 'string'){
                    $imagesUrl[]=$file;
                }else{
                    $path=$file->store('public');
                    $imagesUrl[]=$path;
                }
                
                
            } 
        }else {
            $path=$uploadFile->store('public');
            $imagesUrl=$path;
        }

        if(count($imagesUrl) > 1){
            $product->images->delete();
        }
        foreach($imagesUrl as $url){
            Image::create([
                'url'=>$url,
                'product_id'=>$product->id
            ]);
        }
            return response()->json([
                'message'=>"Update Image Successful"
            ]);
        }
    
}
