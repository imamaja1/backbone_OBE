<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProdiSIPPResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'nama_prodi' => $this->nama_program_studi,
            'nama_fakultas' => $this->fakultas->nama_fakultas
        ];
    }
}
