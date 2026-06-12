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
        Schema::table('observasi_harians', function (Blueprint $table) {
            $table->text('catatan')->nullable()->after('nilai_angka');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('observasi_harians', function (Blueprint $table) {
            $table->dropColumn('catatan');
        });
    }
};
