<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemateris', function (Blueprint $table) {
            $table->foreignId('materi_id')->nullable()->after('id')->constrained('materis')->onDelete('set null');
            $table->string('foto')->nullable()->after('pekerjaan');
            $table->string('jabatan')->nullable()->after('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemateris', function (Blueprint $table) {
            $table->dropForeign(['materi_id']);
            $table->dropColumn(['materi_id', 'foto', 'jabatan']);
        });
    }
};
