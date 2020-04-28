<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    public $table = "cart_items";
    public $timestamps = false;
    protected $guarded = [];
}
