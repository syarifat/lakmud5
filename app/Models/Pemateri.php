<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemateri extends Model
{
    protected $guarded = ['id'];

    public function riwayatPendidikans()
    {
        return $this->hasMany(RiwayatPendidikan::class);
    }

    public function riwayatOrganisasis()
    {
        return $this->hasMany(RiwayatOrganisasi::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}