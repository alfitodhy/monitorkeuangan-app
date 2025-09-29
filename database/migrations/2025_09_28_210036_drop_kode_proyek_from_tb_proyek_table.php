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
        Schema::table('tb_proyek', function (Blueprint $table) {
            if (Schema::hasColumn('tb_proyek', 'kode_proyek')) {
                $table->dropColumn('kode_proyek');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_proyek', function (Blueprint $table) {
            // Jika ingin rollback, bisa ditambahkan kembali kolomnya
            $table->string('kode_proyek', 50)->nullable()->after('id');
        });
    }
};
