<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class appointments extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'name' => $this->driving_instructor,
        ];
    }
}
