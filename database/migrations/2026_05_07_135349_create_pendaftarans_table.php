<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nia')->nullable();
            $table->string('delegasi');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('jabatan');
            $table->string('no_hp');
            $table->string('username_ig');
            $table->string('ukuran_kaos');
            $table->string('file_ttd');
            $table->string('file_sertifikat');
            $table->string('file_rekom');
            $table->string('file_foto');
            $table->string('file_identitas');
            $table->string('file_bukti_ig');
            $table->boolean('status_lulus')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pendaftarans');
    }
};