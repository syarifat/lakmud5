<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
            $table->foreignId('peserta_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('waktu_tap');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('absensis');
    }
};