<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_proyek', function (Blueprint $table) {
            $table->string('lokasi_proyek')->nullable()->after('durasi_pengerjaan_bulan');
        });
    }

    public function down(): void
    {
        Schema::table('tb_proyek', function (Blueprint $table) {
            $table->dropColumn('lokasi_proyek');
        });
    }
};
