<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $table='categories';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'visibility'=>'boolean'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class,ProductCategory::class,"category_id","product_id");
    }
}
