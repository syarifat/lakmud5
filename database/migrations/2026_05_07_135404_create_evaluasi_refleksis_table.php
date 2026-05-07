<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('evaluasi_refleksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('users')->onDelete('cascade');
            $table->integer('hari_ke');
            $table->date('tanggal');
            $table->text('q1_pengalaman');
            $table->text('q2_partisipasi');
            $table->text('q3_hambatan_dorongan');
            $table->text('q4_kesempatan_pendapat');
            $table->text('q5_pengetahuan_didapat');
            $table->text('q6_hambatan_diri_sendiri');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('evaluasi_refleksis');
    }
};