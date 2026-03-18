<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $fileUploadService;
    // Inject the service via constructor
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
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

        if ($request->hasFile('img')) {
            $path = $this->fileUploadService->upload($request->file('img'), 'products');
            $product->pro_image = $path;
        }

        $product->save();

        return response()->json([
                'status' => '200',
                'message' => $product
            ]);
    }

    public function show($pro_id){
        $product = Product::find($pro_id);
        if(!$product){
            return response()->json([
                'status' => '404',
                'message' => 'Product not found'
            ]);
        }
        try{
            return response()->json([
            'status' => '200',
            'data' => $product
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => '500',
                'message' => 'An error occurred while fetching the product'
            ]);
        }

    }

    public function update(Request $request, $pro_id){
        $product = Product::find($pro_id);
        if(!$product){
            return response()->json([
                'status' => '404',
                'message' => 'Product not found'
            ]);
        }

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

        $product->pro_name = $request->input('name');
        $product->pro_price = $request->input('price');
        $product->pro_desc = $request->input('descr');
        $product->pro_stock = $request->input('stock');
        $product->pro_status = $request->input('status');

        if ($request->hasFile('img')) {
            // Delete old image if exists
            if ($product->pro_image) {
                $this->fileUploadService->delete($product->pro_image);
            }
            // Upload new image
            $path = $this->fileUploadService->upload($request->file('img'), 'products');
            $product->pro_image = $path;
        }

        $product->save();

        return response()->json([
                'status' => '200',
                'message' => $product
            ]);

    }

    public function destroy($pro_id){
      $product = Product::find($pro_id);
      if($product === null){
        return response()->json([
            'status' => 404,
            'message' => 'Product Not Found',
        ], 404);
      }

        // Delete the image file if it exists
        if($product->pro_image){
            $this->fileUploadService->delete($product->pro_image);
        }

        $product->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Product Deleted Successfully',
        ], 200);
    }
}
