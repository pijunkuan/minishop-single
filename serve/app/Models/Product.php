<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $table = "products";
    protected $guarded = [];

    public function content()
    {
        return $this->hasOne(ProductContent::class,"product_id");
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class,"product_id");
    }
}
