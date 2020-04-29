<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    public $table = "product_images";
    protected $guarded = [];
    public $timestamps = false;

    public function image()
    {
        return $this->belongsTo(Image::class,"image_id");
    }

}
