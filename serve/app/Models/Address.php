<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public $table="addresses";
    protected $guarded = [];
    protected $casts = [
        "default"=>"boolean"
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class,"customer_id");
    }

}
