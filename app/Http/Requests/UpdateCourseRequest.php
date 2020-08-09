<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
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
            'name_en' => 'sometimes|nullable',
            'description_en' => 'sometimes|nullable',
            'name_ar' => 'sometimes|nullable',
            'description_ar' => 'sometimes|nullable',
            'level' => 'sometimes|nullable|numeric|in:1,2,3',
            'duration' => 'sometimes|nullable|numeric',
            'days_per_week' => 'sometimes|nullable|numeric',
            'duration_per_section' => 'sometimes|nullable|numeric',
            'difficulty' => 'sometimes|nullable|numeric',
            'is_paid' => 'sometimes|nullable|numeric|in:0,1',
            //'free_videos_num' => 'required_if:is_paid,1|numeric'
        ];
    }
}
