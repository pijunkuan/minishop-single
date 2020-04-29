<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductContent extends Model
{
    public $table = "product_contents";
    protected $guarded = [];
    public $timestamps = false;
    public function product()
    {
        return $this->belongsTo(Product::class,"product_id");
    }
}
