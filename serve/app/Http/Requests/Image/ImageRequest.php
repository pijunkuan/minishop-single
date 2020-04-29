<?php

namespace App\Http\Requests\Image;


use App\Http\Requests\FormRequest;

class ImageRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "file"=>"required|image",
        ];
    }
}
