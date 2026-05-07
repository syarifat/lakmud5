<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiInspel extends Model
{
    protected $guarded = ['id'];

    public function inspel()
    {
        return $this->belongsTo(User::class, 'inspel_id');
    }

    public function peserta()
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }
}