<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_termin_proyek', function (Blueprint $table) {
            // ubah created_at default current_timestamp
            DB::statement('ALTER TABLE tb_termin_proyek 
                MODIFY created_at TIMESTAMP  DEFAULT CURRENT_TIMESTAMP');

            // ubah updated_at default current_timestamp dan auto update
            DB::statement('ALTER TABLE tb_termin_proyek 
                MODIFY updated_at TIMESTAMP  DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        });
    }

    public function down(): void
    {
        Schema::table('tb_termin_proyek', function (Blueprint $table) {
            // kembalikan ke nullable tanpa default
            DB::statement('ALTER TABLE tb_termin_proyek 
                MODIFY created_at TIMESTAMP NULL DEFAULT NULL');

            DB::statement('ALTER TABLE tb_termin_proyek 
                MODIFY updated_at TIMESTAMP NULL DEFAULT NULL');
        });
    }
};
