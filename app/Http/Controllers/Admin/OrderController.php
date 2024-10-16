<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    //create orders
    public function store(){
        $validator=Validator::make(request()->all(),[
            'total_amount'=>['nullable'],
            'address'=>['nullable'],
            'screen_shot'=>['nullable'],
            'notes'=>['nullable'],
            'order_products'=>['required']
           
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>"unprocessable",
                'error'=>$validator->errors()
            ],422);
        }

        if(request('screen_shot')){
            $path=request('screen_shot')->store('public');
        }

        $order=Order::create([
            'status'=>'pending',
            'total_amount'=>request('total_amount'),
            'address'=>request('address'),
            'screen_shot'=>$path ?? null,
            'notes'=>request('notes'),
            'user_id'=>request()->user()->id
        ]);

        foreach(request('order_product') as $product){
            OrderProduct::create([
                'order_id'=>$order->id,
                'product_id'=>$product['product_id'],
                'quantity'=>$product['quantity']
            ]);
        }
        return response()->json([
            'message'=>"Create Successful",
            'orders'=>$order
        ]);
    }

     //update orders
     public function update(Order $order){
        $validator=Validator::make(request()->all(),[
            'status'=>['required'],
           
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>"unprocessable",
                'error'=>$validator->errors()
            ],422);
        }

        if($order->status == 'confirmed'){
            return response()->json([
                'message'=>"Your order is already confirmed"
            ]);
        }

        $order->update([
            'status'=>request('status'),
           
        ]);

        $orderproducts=$order->products;
        foreach($orderproducts as $orderproduct){
            $product=Product::where('id',$orderproduct->id)->first();
            $product->update([
                'quantity'=>$product->quantity - $orderproduct->pivot->quantity
            ]);
        }

        return response()->json([
            'message'=>'Update Successful',
            'order'=>$order
           
        ]);
    }

    //delete orders
    public function delete(Order $order){
        $order->delete();
        return response()->json([
            'message'=>"Delete Successful"
        ]);

    }


     //get all products
     public function show(){
        $order=Order::with('user')->get();
        return response()->json([
            'orders'=>$order
        ]);
    }

    //product details
    public function detail(Order $order){
        $order=Order::where('id',$order->id)->with('products',)->first();
        return response()->json([
            'order'=>$order
        ]);
    }

    
}
