<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        return response()->json([
         'status' => '200',
         'data' => $products
        ]);
    }

    public function store(Request $request){

        // return response()->json($request->all());

        $validate = Validator::make($request->all(),[
            //Make sure the validation rules match the actual field names in the request(postman)
            'name' => 'required',
            'price' => 'required|numeric',
            'descr' => 'required',
            'stock' => 'required|numeric',
            'status' => 'required',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => '400',
                'error' => $validate->errors()
            ]);
        }

        $product = new Product();
        $product->pro_name = $request->input('name');
        $product->pro_price = $request->input('price');
        $product->pro_desc = $request->input('descr');
        $product->pro_stock = $request->input('stock');
        $product->pro_status = $request->input('status');
        if($request->hasFile(key: 'img')){
            $image = $request->file(key: 'img');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $product->pro_image = $imageName;
        }
        $product->save();
        return response()->json([
                'status' => '200',
                'message' => $product
            ]);
        }
}
