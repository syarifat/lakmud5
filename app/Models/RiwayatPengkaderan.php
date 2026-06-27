<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPengkaderan extends Model
{
    protected $guarded = ['id'];

    public function pemateri()
    {
        return $this->belongsTo(Pemateri::class);
    }
}
