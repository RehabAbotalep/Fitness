<?php

namespace App\Http\Controllers\Api\Trainer;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCourseRequest;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{   
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCourseRequest $request)
    {
        $data = $request->except('name_en','name_ar','description_en','description_ar');

        $data['en'] = ['name' => $request->name_en,'description' => $request->description_en];
        $data['ar'] = ['name' => $request->name_ar,'description' => $request->description_ar];
        
        $course = auth('api')->user()->courses()->create($data);

        return $this->dataResponse($course->hashid(),trans('all.submitted'),200);
    }

    /**
     * store videos for course.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeVideo(StoreVideoRequest $request)
    {
        $course = Course::findByHashidOrFail($request->course_id);

        $data = [
            'en' => ['title' => $request->title_en,'body_focus' => $request->body_focus_en],
            'ar' => ['title' => $request->title_ar,'body_focus' => $request->body_focus_ar]
        ];
        $video = $course->videos()->create($data);
        $video->addMedia($request->video)->toMediaCollection('video');
        return $this->dataResponse(null,trans('all.submitted'),200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
