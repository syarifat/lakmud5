<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('observasi_harians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pendamping_id')->constrained('users')->onDelete('cascade');
            $table->integer('hari_ke');
            $table->integer('kedisiplinan');
            $table->integer('kemampuan');
            $table->integer('keaktifan');
            $table->decimal('nilai_angka', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('observasi_harians');
    }
};