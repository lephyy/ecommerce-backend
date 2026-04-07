<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'pro_id'; // Due to the custom primary key name in the database table so we need to specify it here

    public function cartItems(){
        return $this->hasMany(CartItem::class, 'pro_id', 'pro_id');
    }
}
