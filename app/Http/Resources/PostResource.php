<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
   //define properti
   public $status;
   public $message;
   public $resource;

   //constructor untuk menginisialisasi properti

   public function __construct($status, $message, $resource)
   {
       $this->status = $status;
       $this->message = $message;
       $this->resource = $resource;
   }
   //return
    public function toArray($request)
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->resource
        ];
    }

}
