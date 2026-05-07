<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $guarded = ['id'];

    public function pendamping()
    {
        return $this->belongsTo(User::class, 'pendamping_id');
    }

    public function pesertas()
    {
        return $this->belongsToMany(User::class, 'peserta_kelompoks', 'kelompok_id', 'peserta_id');
    }
}