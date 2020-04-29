<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $table = "images";
    protected $guarded = [];
    public $timestamps = false;

    public function getUrlAttribute()
    {
        if($this->img_file){
            return asset($this->img_file);
        }elseif($this->img_link){
            return $this->img_link;
        }else{
            return null;
        }

    }
}
