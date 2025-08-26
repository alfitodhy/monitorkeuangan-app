<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_pemasukan_proyek', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_pemasukan_proyek', 'id_termin')) {
                $table->unsignedBigInteger('id_termin')->nullable()->after('id_proyek');
            }

            $table->foreign('id_termin')
                ->references('id_termin')
                ->on('tb_termin_proyek')
                ->onDelete('set null')   // supaya kalau termin dihapus, pemasukan tetap ada tapi id_termin null
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tb_pemasukan_proyek', function (Blueprint $table) {
            $table->dropForeign(['id_termin']);
            $table->dropColumn('id_termin');
        });
    }
};
