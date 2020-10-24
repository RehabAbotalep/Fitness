<?php

namespace App;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Video extends Model implements HasMedia
{
    use HasHashid , Translatable , InteractsWithMedia;

    public $translatedAttributes = [ 'title' , 'body_focus'];

    
}
