<?php

namespace App\Http\Controllers\Api\Trainer;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Traits\ApiResponse;
use App\Http\Transformer\CourseTransformer;
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
        $courses =  fractal()
                    ->collection(auth('api')->user()->courses()->orderBy('id','DESC')->get())
                    ->transformWith(new CourseTransformer())
                    ->serializeWith(new \Spatie\Fractalistic\ArraySerializer())
                    ->includeVideos()
                    ->toArray();

        return $this->dataResponse($courses,null,200);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course =  fractal()
                    ->item(Course::findByHashidOrFail($id))
                    ->transformWith(new CourseTransformer())
                    ->serializeWith(new \Spatie\Fractalistic\ArraySerializer())
                    ->includeVideos()
                    ->toArray();

        return $this->dataResponse($course,null,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseRequest $request, $id)
    {
        $course = Course::findByHashidOrFail($id);
        $data = $request->except('name_en','name_ar','description_en','description_ar');

        if( !empty($request->name_en))
        {
            $data['en'] = ['name' => $request->name_en];
        }

        if( !empty($request->name_ar))
        {
            $data['ar'] = ['name' => $request->name_ar];
        }

        if( !empty($request->description_en))
        {
            $data['en'] = ['description' => $request->description_en];
        }

        if( !empty($request->description_ar))
        {
            $data['ar'] = ['description' => $request->description_ar];
        }

        $course->update($data);
        return $this->dataResponse(null,trans('all.updated'),200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::findByHashidOrFail($id);

        foreach ($course->videos as $video) {
            $video->clearMediaCollection('video');
        }   
        $course->delete();
        return $this->dataResponse(null,trans('all.deleted'),200);
    }


}
