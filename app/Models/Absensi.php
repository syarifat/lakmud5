<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $guarded = ['id'];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function peserta()
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }
}