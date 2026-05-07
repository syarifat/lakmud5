<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObservasiHarian extends Model
{
    protected $guarded = ['id'];

    public function peserta()
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }

    public function pendamping()
    {
        return $this->belongsTo(User::class, 'pendamping_id');
    }
}