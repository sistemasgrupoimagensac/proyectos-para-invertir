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
            'id'                => $this->id,
            'inversionista_id'  => $this->inversionista_id,
            'name'              => $this->name,
            'email'             => $this->email,
            'celular'           => optional($this->persona)->nu_celular,
            'dni'               => optional($this->persona)->nu_documento_identidad,
            'direccion'         => optional($this->persona)->no_direccion_ultima,
            'api_token'         => $this->api_token,
        ];
    }
}
