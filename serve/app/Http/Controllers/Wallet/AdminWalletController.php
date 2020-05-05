<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\WalletStoreRequest;
use App\Http\Resources\Wallet\WalletCollection;
use App\Models\Customer;
use App\Models\Wallet;
use Illuminate\Http\Request;

class AdminWalletController extends Controller
{
    public function index(Customer $customer, Request $request)
    {
        $wallets = $customer->wallets()->orderBy('created_at', 'desc');
        return $this->jsonSuccessResponse(new WalletCollection($wallets->paginate($request->get('pageSize'))));
    }

    public function store(Customer $customer,WalletStoreRequest $request)
    {
        $amount = 0;
        $type = $request->get('type');
        switch($type){
            case Wallet::WALLET_IN:
                $amount = abs($request->get('amount'));
                break;
            case Wallet::WALLET_OUT:
                $amount = 0 - abs($request->get('amount'));
                break;
        }
        $content = $request->get('content');
        $wallet = $customer->wallets()->create([
           "amount"=>$amount,
           "type"=>$type,
           "content"=>$content
        ]);
        return $this->jsonSuccessResponse($wallet);
    }

    public function balance(Customer $customer)
    {
        return $this->jsonSuccessResponse([
            "balance"=>$customer->wallets()->sum('amount')
        ]);
    }
}
