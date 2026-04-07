<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index(Request $request){
         $session_id = Str::random(5);

        return response()->json([
            'status' => 200,
            'session_id' => $session_id
        ]);
    }

    public function addToCart(Request $request){
        $session_id = $request->session_id;

        $request->validate([
            'session_id' => 'required|string',
            'pro_id'     => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        // find or create cart
        $cart = Cart::firstOrCreate([
            'session_id' => $session_id
        ]);

        // check product
        try {
            $product = Product::where('pro_id', $request->pro_id)->firstOrFail();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Product does not exist'
            ],404);
        }

        // check if product already in cart
        $cartItem = CartItem::where('cart_id',$cart->id)
                ->where('pro_id',$request->pro_id)
                ->first();

        if($cartItem){
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        }else{
            CartItem::create([
                'cart_id' => $cart->id,
                'pro_id' => $request->pro_id,
                'quantity' => $request->quantity
            ]);
        }
        return response()->json([
            'message' => 'Product added to cart'
        ]);
    }

    public function getCart($session_id){
        $cart = Cart::where('session_id',$session_id)
                ->with('items.product')
                ->first();
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        return response()->json($cart);

    }
}
