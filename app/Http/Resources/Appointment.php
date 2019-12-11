<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;
use App\User;

class Appointment extends JsonResource
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
            'id' => $this->id,
            "driving_instructor" => new UserResource(User::find($this->driving_instructor))  ,
            "student" => new UserResource(User::find($this->student)) ,
            "status" => $this->status,
            "description" =>  $this->description,
            "start_time" => $this->start_time,
            "end_time" => $this->end_time,
        ];

    }
}
