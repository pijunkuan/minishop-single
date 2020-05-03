<?php

namespace App\Http\Requests\Product;


use App\Http\Requests\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "product"=>"required|array",
            "product.product_title"=>'required',
            "product.product_unit"=>"nullable",
            "product.product_des"=>"nullable",
            "product.on_sale"=>"required|boolean",
            "content"=>"nullable",
            "images"=>"nullable|array",
            "images.*.image_id"=>"required|exists:images,id",
            "images.*.sort"=>"required",
            "variants"=>"required|array",
            "variants.*.id"=>"nullable|exists:product_variants,id",
            "variants.*.variant_title"=>"required",
            "variants.*.variant_code"=>"nullable",
            "variants.*.ori_price"=>"nullable|numeric",
            "variants.*.weight"=>"nullable|numeric",
            "variants.*.price"=>"required|numeric",
            "variants.*.buy_price"=>"nullable|numeric",
            "variants.*.quantity"=>"required|numeric",
            "categories"=>"nullable|array",
            "categories.*"=>[
                "nullable",
                "exists:categories,id"
            ]
        ];
    }

    public function attributes()
    {
        return [
            "product"=>"商品信息",
            "product.product_title"=>'商品名称',
            "product.product_unit"=>"商品单位",
            "product.product_des"=>"商品描述",
            "product.on_sale"=>"商品上架状态",
            "content"=>"商品详情",
            "images.*.image_id"=>"图片ID",
            "images.*.sort"=>"图片排序",
            "variants"=>"商品规格",
            "variants.*.variant_title"=>"商品规格名称",
            "variants.*.variant_code"=>"商品规格编码",
            "variants.*.ori_price"=>"商品规格原价",
            "variants.*.price"=>"商品规格售价",
            "variants.*.weight"=>"商品重量",
            "variants.*.buy_price"=>"商品规格采购价",
            "variants.*.quantity"=>"商品规格库存",
            "categories"=>"商品分类",
            "categories.*"=>"分类id"
        ];
    }
}
