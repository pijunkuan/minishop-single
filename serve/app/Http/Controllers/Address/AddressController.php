<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\AddressStoreRequest;
use App\Http\Resources\Address\AddressCollection;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{

    public function index(Request $request)
    {
        $addresses = auth('customers')->user()->addresses();
        if ($request->get('name')) $addresses = $addresses->where('name', 'like', "%{$request->get('name')}%");
        $addresses = $addresses->orderBy('default', 'desc')->paginate($request->get('pageSize'));
        return $this->jsonSuccessResponse(new AddressCollection($addresses));
    }

    public function store(AddressStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $address = [
                "name" => $request->get("name"),
                "province" => $request->get("province"),
                "district" => $request->get("district"),
                "city" => $request->get("city"),
                "detail" => $request->get("detail"),
                "mobile" => $request->get("mobile"),
                "zip" => $request->get("zip"),
                "default" => $request->get('default') ? true : false
            ];
            $customer = auth('customers')->user();
            if ($address['default'])
                $customer->addresses()->update(['default' => false]);
            if ($customer->addresses()->count() == 0)
                $address['default'] = true;
            $customer->addresses()->create($address);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->jsonErrorResponse(401, $exception->getMessage());
        }
        return $this->jsonSuccessResponse(null, "地址创建成功");
    }

    public function update($address, AddressStoreRequest $request)
    {
        if ($address = auth('customers')->user()->addresses()->find($address)) {
            DB::beginTransaction();
            try {
                $temp = [
                    "name" => $request->get("name"),
                    "province" => $request->get("province"),
                    "district" => $request->get("district"),
                    "city" => $request->get("city"),
                    "detail" => $request->get("detail"),
                    "mobile" => $request->get("mobile"),
                    "zip" => $request->get("zip"),
                    "default" => $request->get('default') ? true : false
                ];
                if ($temp['default']) {
                    auth('customers')->user()->addresses()->update(['default' => false]);
                } elseif (auth('customers')->user()->addresses()->count() == 1) {
                    $temp['default'] = true;
                } elseif ($address->default) {
                    $temp['default'] = true;
                }
                $address->update($temp);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                return $this->jsonErrorResponse(401, $exception->getMessage());
            }

            return $this->jsonSuccessResponse();
        } else {
            return $this->jsonErrorResponse(401, "没有此记录");
        }
    }

    public function destroy($address)
    {
        if ($address = auth('customers')->user()->addresses()->find($address)) {
            $address->delete();
            if (auth('customers')->user()->addresses()->count() > 0)
                if (auth('customers')->user()->addresses()->where('default', 1)->count() == 0)
                    auth('customers')->user()->addresses()->first()->update(['default' => true]);
            return $this->jsonSuccessResponse();
        } else {
            return $this->jsonErrorResponse(401, "没有此记录");
        }
    }
}
