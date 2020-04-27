<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    const WALLET_IN = "in";
    const WALLET_OUT = "out";

    const walletTypeMap = [
        self::WALLET_IN => "收入",
        self::WALLET_OUT => "支出"
    ];

    public $table = "wallets";
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class,"customer_id");
    }
}
