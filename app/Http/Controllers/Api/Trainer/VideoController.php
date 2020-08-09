<?php

namespace App\Http\Controllers\Api\Trainer;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Traits\ApiResponse;
use App\Http\Transformer\VideoTransformer;
use App\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    use ApiResponse;
    
    
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
        $video =  fractal()
                    ->item(Video::findByHashidOrFail($id))
                    ->transformWith(new VideoTransformer())
                    ->serializeWith(new \Spatie\Fractalistic\ArraySerializer())
                    ->toArray();

        return $this->dataResponse($video,null,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateVideo(Request $request, $id)
    {
        $video = Video::findByHashidOrFail($id);

        $request->validate([
            'video' => 'mimes:mp4,mov,ogg,qt|max:20000'

        ]);

        if( !empty($request->title_en))
        {
            $data['en'] = ['title' => $request->title_en];
        }

        if( !empty($request->title_ar))
        {
            $data['ar'] = ['title' => $request->title_ar];
        }

        if( !empty($request->body_focus_en))
        {
            $data['en'] = ['body_focus' => $request->body_focus_en];
        }

        if( !empty($request->body_focus_ar))
        {
            $data['ar'] = ['body_focus' => $request->body_focus_ar];
        }

        $video->update($data);
        if( $request->hasFile('video') )
        {
            $video->clearMediaCollection('video');
            $video->addMedia($request->video)->toMediaCollection('video');
        }
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
        $video = Video::findByHashidOrFail($id);
        $video->clearMediaCollection('video');   
        $video->delete();
        return $this->dataResponse(null,trans('all.deleted'),200);
    }
}
