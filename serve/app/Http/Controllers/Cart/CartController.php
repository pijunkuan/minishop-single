<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddCartRequest;
use App\Http\Resources\Cart\CartItemResource;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = auth('customers')->user()->cartItems;
        return $this->jsonSuccessResponse(CartItemResource::collection($cartItems));
    }

    public function store(AddCartRequest $request)
    {
        $quantity = $request->get('quantity');
        $customer = auth('customers')->user();
        if($cart_item = $customer->cartItems()->where('variant_id',$request->get('variant_id'))->first()){
           $cart_item->update([
               "quantity"=>$cart_item->quantity + $quantity
           ]);
        }else{
            $variant = ProductVariant::find($request->get('variant_id'));
            $cart_item = new CartItem([
                "customer_id"=>$customer['id'],
                "variant_id"=>$variant['id'],
                "product_title"=>$variant->product['product_title'],
                "variant_title"=>$variant['variant_title'],
                "product_unit"=>$variant->product['product_unit'],
                "price"=>$variant['price'],
                "quantity"=>$quantity
            ]);
            $cart_item->save();
        }
        return $this->jsonSuccessResponse($cart_item);
    }

    public function update(AddCartRequest $request)
    {
        if($cart_item = auth('customers')->user()->cartItems()->where('variant_id',$request->get('variant_id'))->first()){
            $cart_item->update(['quantity'=>$request->get('quantity')]);
        }else{
            return $this->jsonErrorResponse(401,"购物车不存在该商品");
        }
        return $this->jsonSuccessResponse();
    }

    public function destroy($variant_id)
    {
        auth('customers')->user()->cartItems()->where('variant_id',$variant_id)->delete();
        return $this->jsonSuccessResponse();
    }
}
