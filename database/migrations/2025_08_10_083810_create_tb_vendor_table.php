<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_vendor', function (Blueprint $table) {
            $table->id('id_vendor');
            $table->string('kode_vendor', 50);
            $table->string('nama_vendor', 150);
            $table->string('no_telp', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('jenis_vendor', 100)->nullable();
            $table->string('spesialisasi', 150)->nullable();
            $table->json('rekening')->nullable(); // simpan banyak rekening dalam format JSON
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_vendor');
    }
};
