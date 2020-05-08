<?php

namespace App\Http\Requests\Category;


use App\Http\Requests\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "category_title" => "required",
            "visibility" => "required|boolean"
        ];
    }

    public function attributes()
    {
        return [
            "category_title" => "分类名称",
            "visibility" => "是否可见"
        ];
    }
}
