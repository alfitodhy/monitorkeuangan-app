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
        Schema::table('tb_termin_proyek', function (Blueprint $table) {
            $table->string('kategori_termin')->default('termin awal')->after('tanggal_lunas');
            // lo bisa ganti after('id') sesuai kolom terakhir yang cocok
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_termin_proyek', function (Blueprint $table) {
            $table->dropColumn('kategori_termin');
        });
    }
};
