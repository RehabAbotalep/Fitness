<?php

namespace App\Http\Controllers\Api\Admin;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Http\Transformer\CourseTransformer;
use App\Notifications\ApproveCourse;
use App\Notifications\CancelCourse;
use App\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use ApiResponse;

    //get All Courses
    public function allCourses()
    {
    	$courses =  fractal()
                    ->collection(Course::orderBy('id','DESC')->get())
                    ->transformWith(new CourseTransformer())
                    ->serializeWith(new \Spatie\Fractalistic\ArraySerializer())
                    ->includeVideos()
                    ->toArray();


        return $this->dataResponse($courses,null,200);
    }

    public function approveCourse($id)
    {
    	$course = Course::findByHashidOrFail($id);
    	$course->update(['is_approved' => 1]);
    	User::find($course->user_id)->notify(new ApproveCourse());
    	return $this->dataResponse(null,trans('all.approved'),200);
    }

    public function cancelCourse($id)
    {
    	$course = Course::findByHashidOrFail($id);
    	$course->update(['is_approved' => 0]);
    	User::find($course->user_id)->notify(new CancelCourse());
    	return $this->dataResponse(null,trans('all.cancelled'),200);
    }
}
