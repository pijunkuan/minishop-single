<?php

namespace App\Http\Controllers\Image;

use App\Http\Controllers\Controller;
use App\Http\Requests\Image\ImageRequest;
use App\Http\Resources\Image\ImageCollection;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $images = new Image();
        $images = $images->orderBy('id','desc')->paginate($request->get('pageSize'));
        return $this->jsonSuccessResponse(new ImageCollection($images));
    }

    public function store(ImageRequest $request)
    {
        $file = $request->file('file');
        $fileName = date('YmdHis').str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT).".".$file->getClientOriginalExtension();
        $savePath = "images/".$fileName;
        Storage::put($savePath,File::get($file));
        $img = Image::create([
            "img_file"=>$savePath,
            "img_name"=>$fileName,
            "img_bytes"=>$file->getSize()
        ]);
        return $this->jsonSuccessResponse($img);
    }

    public function destroy(Image $image)
    {
        if($image->img_file){
            Storage::delete($image->img_file);
            $image->delete();
        }
        return $this->jsonSuccessResponse();

    }
}
