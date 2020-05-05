<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\CustomerCollection;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = new Customer();
        if ($mobile = $request->get('mobile'))
            $customers = $customers->where('mobile', 'like', "%{$mobile}%");
        if ($username = $request->get('username'))
            $customers = $customers->where('username', 'like', "%{$username}%");
        $customers = $customers->orderBy('created_at', 'desc')->paginate($request->get('pageSize'));
        return $this->jsonSuccessResponse(new CustomerCollection($customers));
    }

    public function update(Customer $customer, Request $request)
    {
        if ($request->get('password'))
            $customer->password = Hash::make($request->get('password'));
        $customer->save();
        return $this->jsonSuccessResponse();
    }


}
