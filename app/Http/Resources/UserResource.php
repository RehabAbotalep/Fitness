<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'         => $this->name,
            'email'        => $this->email,
            'gender'       => $this->gender,
            'dob'          => $this->dob,
            'weight'       => $this->weight,
            'height'       => $this->height,
            'goal'         => (int)$this->goal,
            'is_verified'  => (int)$this->is_verified,
            'is_completed' => (int)$this->is_completed,
            'is_approved'  => (int)$this->is_approved,

        ];
    }
}
