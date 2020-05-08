<?php

namespace App\Http\Controllers\Template;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function get(){
        $file = 'template\template.json';
        $fileExists = Storage::exists($file);
        if(!$fileExists) return $this->jsonErrorResponse(401,"没有此主题文件");
        $json = Storage::get($file);
        $json = json_decode($json,true);
        return $this->jsonSuccessResponse($json);
    }

    public function put(Request $request)
    {
        $file = 'template\template.json';
        if($request->has('template')){
            $theme = $request->get('template');
            Storage::put($file,json_encode($theme));
        }
        return $this->jsonSuccessResponse();
    }
}
