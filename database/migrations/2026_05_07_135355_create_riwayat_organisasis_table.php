<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('riwayat_organisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemateri_id')->constrained('pemateris')->onDelete('cascade');
            $table->string('nama_organisasi');
            $table->string('jabatan');
            $table->string('tahun');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('riwayat_organisasis');
    }
};