<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class book extends JsonResource
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
           'title' => $this->title,
           'isbn' => $this->isbn,
           'details' => route('details',['isbn'=>$this->isbn]),
           'delete' => route('delete',['isbn'=>$this->isbn]),
           ];


    }
}
