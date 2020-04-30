<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Resources\Product\AdminProductCollection;
use App\Http\Resources\Product\AdminProductDetailResource;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = new Product();
        $products = $products->paginate($request->get('pageSize'));
        return $this->jsonSuccessResponse(new ProductCollection($products));
    }

    public function show(Product $product)
    {
        return $this->jsonSuccessResponse(new ProductDetail($product));
    }


}

