<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pemateris', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('hobi');
            $table->string('motto');
            $table->string('no_telp');
            $table->string('pekerjaan');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pemateris');
    }
};