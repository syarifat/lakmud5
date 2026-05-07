<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    protected $guarded = ['id'];

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function jawabanTes()
    {
        return $this->hasMany(JawabanTes::class);
    }
}