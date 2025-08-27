<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('tb_pemasukan_proyek', function (Blueprint $table) {
            $table->bigInteger('jumlah')->change();
        });

        Schema::table('tb_pengeluaran_proyek', function (Blueprint $table) {
            $table->bigInteger('jumlah')->change();
        });

        Schema::table('tb_proyek', function (Blueprint $table) {
            $table->bigInteger('nilai_proyek')->change();
            $table->bigInteger('estimasi_hpp')->change();
        });

        Schema::table('tb_termin_proyek', function (Blueprint $table) {
            $table->bigInteger('jumlah')->change();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('tb_pemasukan_proyek', function (Blueprint $table) {
            $table->decimal('jumlah', 18, 2)->change();
        });

        Schema::table('tb_pengeluaran_proyek', function (Blueprint $table) {
            $table->decimal('jumlah', 18, 2)->change();
        });

        Schema::table('tb_proyek', function (Blueprint $table) {
            $table->decimal('jumlah', 18, 2)->change();
            $table->decimal('estimasi_hpp', 18, 2)->change();
        });

        Schema::table('tb_termin_proyek', function (Blueprint $table) {
            $table->decimal('jumlah', 18, 2)->change();
        });
    }
};
