<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public $table="customers";
    protected $guarded = [];

    public function address()
    {
        return $this->hasMany(Customer::class,"customer_id");
    }

    public function wallet()
    {
        return $this->hasMany(Wallet::class,"customer_id");
    }
}
