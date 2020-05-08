<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThemeController extends Controller
{
    public function get(){
        $file = 'theme\theme.json';
        $fileExists = Storage::exists($file);
        if(!$fileExists){
            Storage::put($file,json_encode(["theme"=>null]));
        }
        $json = Storage::get($file);
        $json = json_decode($json,true);
        return $this->jsonSuccessResponse($json);
    }

    public function put(Request $request)
    {
        $file = 'theme\theme.json';
        $theme = $request->get('theme');
        if($theme){
            Storage::put($file,json_encode($theme));
        }
        return $this->jsonSuccessResponse();
    }
}
