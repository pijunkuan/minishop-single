<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductVariant extends Model
{
    public $table="product_variants";
    protected $guarded = [];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class,"product_id");
    }

    public function decreaseStock($quantity)
    {
        if ($quantity < 0) {
            throw (new HttpResponseException(response()->json([
                'code'=>422,
                "msg"=>"减库存不可小于0",
                "data"=>null,
            ],422)));
        }
        return $this->where('id', $this->id)->where('quantity', '>=', $quantity)->decrement('quantity', $quantity);
    }

    public function addStock($quantity)
    {
        if ($quantity < 0) {
            throw (new HttpResponseException(response()->json([
                'code'=>422,
                "msg"=>"加库存不可小于0",
                "data"=>null,
            ],422)));
        }
        return $this->where('id', $this->id)->increment('quantity', $quantity);
    }
}
