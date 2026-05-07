<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanTes extends Model
{
    protected $guarded = ['id'];

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function peserta()
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }
}