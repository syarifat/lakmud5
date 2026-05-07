<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluasiRefleksi extends Model
{
    protected $guarded = ['id'];

    public function peserta()
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }
}