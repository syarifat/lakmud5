<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $guarded = ['id'];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function bankSoals()
    {
        return $this->hasMany(BankSoal::class);
    }
}