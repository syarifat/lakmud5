<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaKelompok extends Model
{
    protected $guarded = ['id'];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function peserta()
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }
}