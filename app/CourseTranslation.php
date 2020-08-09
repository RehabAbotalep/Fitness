<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [ 'name', 'description' , 'locale' ];
}
