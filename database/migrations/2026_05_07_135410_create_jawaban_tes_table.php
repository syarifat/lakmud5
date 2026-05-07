<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jawaban_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_soal_id')->constrained('bank_soals')->onDelete('cascade');
            $table->foreignId('peserta_id')->constrained('users')->onDelete('cascade');
            $table->text('jawaban');
            $table->integer('nilai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('jawaban_tes');
    }
};