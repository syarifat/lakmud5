<?php

namespace Database\Seeders;

use App\Models\Materi;
use Illuminate\Database\Seeder;

class MateriSeeder extends Seeder
{
    public function run(): void
    {
        $materis = [
            ['nama_materi' => 'Ke-ASWAJA-an 2'],
            ['nama_materi' => 'Ke-NU-an 2'],
            ['nama_materi' => 'Ke-INDONESIA-an 2'],
            ['nama_materi' => 'Ke-IPNU IPPNU-an 2'],
            ['nama_materi' => 'Tradisi Amaliyah NU'],
            ['nama_materi' => 'Kepemimpinan'],
            ['nama_materi' => 'Manajemen Organisasi'],
            ['nama_materi' => 'Komunikasi dan Kerjasama'],
            ['nama_materi' => 'Scientific Problem Solving (SPS)'],
            ['nama_materi' => 'Teknik Diskusi dan Persidangan'],
            ['nama_materi' => 'Teknik Pembuatan TOR dan Proposal'],
            ['nama_materi' => 'Manajemen Konflik'],
            ['nama_materi' => 'Networking and Lobbying'],
            ['nama_materi' => 'Analisis Gender'],
        ];

        foreach ($materis as $m) {
            \App\Models\Materi::create($m);
        }
    }
}