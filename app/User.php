<?php

namespace App;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pengguna;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role','key_ref',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function isRole($check_role)
    {
        $role = explode('|',$check_role);
        $user_roles = self::where('id',Auth::user()->id)->whereIn('role',$role)->first();
        return $user_roles ? true : false;
    }

    public function mahasiswa(){
        return $this->hasOne(Mahasiswa::class,'nim','key_ref');
    }

    public function dosen(){
        return $this->hasOne(Dosen::class,'kode_dosen','key_ref');
    }

    public function pengguna(){
        return $this->hasOne(Pengguna::class,'kode_pengguna','key_ref');
    }
}
