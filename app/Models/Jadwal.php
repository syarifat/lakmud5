<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $guarded = ['id'];

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function pemateri()
    {
        return $this->belongsTo(Pemateri::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function penilaianPesertas()
    {
        return $this->hasMany(PenilaianPeserta::class);
    }

    public function nilaiPemateris()
    {
        return $this->hasMany(NilaiPemateri::class);
    }
}