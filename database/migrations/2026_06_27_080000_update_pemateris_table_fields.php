<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pemateris', function (Blueprint $table) {
            // Drop old unused columns
            $table->dropColumn(['hobi', 'pekerjaan', 'jabatan']);
            
            // Add new requested columns
            $table->string('instagram')->nullable()->after('no_telp');
            $table->string('email')->nullable()->after('instagram');
        });

        // Create riwayat_pengkaderans table
        Schema::create('riwayat_pengkaderans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemateri_id')->constrained('pemateris')->onDelete('cascade');
            $table->string('tingkat');
            $table->string('nama');
            $table->string('tahun');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('riwayat_pengkaderans');
        
        Schema::table('pemateris', function (Blueprint $table) {
            $table->dropColumn(['instagram', 'email']);
            $table->string('hobi')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('jabatan')->nullable();
        });
    }
};
