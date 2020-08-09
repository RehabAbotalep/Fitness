<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [ 'title' , 'body_focus' , 'locale' ];
}
