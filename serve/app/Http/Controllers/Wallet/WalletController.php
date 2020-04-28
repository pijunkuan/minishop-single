<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Wallet\WalletCollection;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $wallets = auth('customers')->user()->wallets()->orderBy('created_at','desc');
        return $this->jsonSuccessResponse(new WalletCollection($wallets->paginate($request->get('pageSize'))));
    }

    public function balance()
    {
        $balance = auth('customers')->user()->wallets()->sum('amount');
        return $this->jsonSuccessResponse([
            'balance' => $balance
        ]);
    }
}
