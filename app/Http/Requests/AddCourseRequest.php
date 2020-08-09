<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_en' => 'required',
            'description_en' => 'required',
            'name_ar' => 'required',
            'description_ar' => 'required',
            'level' => 'required|numeric|in:1,2,3',
            'duration' => 'required|numeric',
            'days_per_week' => 'required|numeric',
            'duration_per_section' => 'required|numeric',
            'difficulty' => 'required|numeric',
            'is_paid' => 'required|numeric|in:0,1',
            'free_videos_num' => 'required_if:is_paid,1|numeric'
            
        ];
    }
}
