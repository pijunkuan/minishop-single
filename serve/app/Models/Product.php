<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $table = "products";
    protected $guarded = [];

    protected $casts = [
      "on_sale"=>"boolean"
    ];

    public function content()
    {
        return $this->hasOne(ProductContent::class,"product_id");
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class,"product_id");
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class,"product_id");
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,ProductCategory::class,"product_id","category_id");
    }
}
