<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('penilaian_pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
            $table->foreignId('peserta_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('inspel_id')->constrained('users')->onDelete('cascade');
            $table->integer('pemahaman');
            $table->integer('kedisiplinan');
            $table->integer('keaktifan');
            $table->decimal('rerata', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('penilaian_pesertas');
    }
};