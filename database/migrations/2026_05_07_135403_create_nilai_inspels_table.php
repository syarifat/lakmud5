<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nilai_inspels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspel_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('peserta_id')->constrained('users')->onDelete('cascade');
            $table->integer('nilai');
            $table->text('catatan_khusus')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('nilai_inspels');
    }
};