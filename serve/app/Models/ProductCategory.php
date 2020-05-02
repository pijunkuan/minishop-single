<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    public $table = 'product_categories';
    public $timestamps = false;
    protected $guarded = [];
}
