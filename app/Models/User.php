<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'peserta_id');
    }

    public function penilaianPesertas()
    {
        return $this->hasMany(PenilaianPeserta::class, 'peserta_id');
    }

    public function kelompoks()
    {
        return $this->belongsToMany(Kelompok::class, 'peserta_kelompoks', 'peserta_id', 'kelompok_id');
    }

    public function kelompokDidampingi()
    {
        return $this->hasMany(Kelompok::class, 'pendamping_id');
    }
}