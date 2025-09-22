<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DosenSIPPResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'status_login' => ($this->status_login == 'A' ? 0 : 1),
            'nama_dosen' => $this->nama_dosen,
            'alamat_email' => $this->alamat_email,
            'nik' => $this->nik,
            'field_studi' => $this->field_studi,
            'alumni' => $this->alumni,
            'no_telp' => $this->no_telp,
            'prodi' => new ProdiSIPPResource($this->prodi),
            'user' => new UserSIPPResource($this->user)
        ];
    }
}
