<?php  

namespace App\Http\Transformer;


use App\Course;
use App\Http\Transformer\VideoTransformer;
use League\Fractal\TransformerAbstract;

class CourseTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['videos'];

    public function __construct($paramter = false)
    {
        $this->paramter = $paramter;
    }

    public function transform(Course $course)
    {
        $array = [
            'id'   => $course->hashid(),
            'name' => $course->name,
            'description'  => $course->description,
            'duration'     => $course->duration,
            'days_per_week'=> $course->days_per_week,
            'duration_per_section' => $course->duration_per_section,
            'difficulty' => $course->difficulty,
            'level'   => $course->level,
            'is_paid' => $course->is_paid,
            'free_videos_num' => $course->free_videos_num,
            'is_single'  => (int)$course->is_single,
            'is_approved'  => (int)$course->is_approved,
            
        ];

        return $array;
    }

    public function includeVideos(Course $course)
    {
        $videos = $course->videos()->get();
        return $this->collection($videos, new VideoTransformer) ; 
    }



}




?>