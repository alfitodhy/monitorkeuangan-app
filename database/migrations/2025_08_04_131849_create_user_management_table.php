<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_user', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama_lengkap', 150);
            $table->string('username', 100)->unique();
            $table->string('password');
            $table->string('email', 150)->nullable();
            $table->string('no_telp', 50)->nullable();
            $table->string('foto_profil', 255)->nullable();
            $table->string('role', 50); // contoh: admin, manager, staff
            $table->char('status_aktif', 10)->default('aktif'); // aktif / tidak aktif
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_user');
    }
};
