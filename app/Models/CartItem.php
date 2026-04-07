<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    //
    protected $fillable = ['cart_id', 'pro_id', 'quantity'];
    public function product(){
        return $this->belongsTo(Product::class, 'pro_id', 'pro_id');
    }
}
