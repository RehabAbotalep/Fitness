<?php

namespace App;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;

class Course extends Model
{
    use HasHashid , Translatable;

    public $translatedAttributes = [ 'name' , 'description'];

    protected $fillable = ['duration' ,'days_per_week' , 'duration_per_section' , 'difficulty' , 
    						'level', 'is_paid' , 'free_videos_num' , 'is_single' , 'is_approved'
    					  ];


   	public function videos()
   	{
   		return $this->hasMany(Video::class);
   	}
}
