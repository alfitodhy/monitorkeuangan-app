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
        Schema::create('tb_pengeluaran_proyek', function (Blueprint $table) {
            $table->id('id_pengeluaran');
           
            $table->unsignedBigInteger('id_proyek');
            $table->string('nama_proyek', 255)->nullable();
           
            $table->unsignedBigInteger('id_vendor');
            $table->string('nama_vendor', 150)->nullable();

            $table->json('rekening')->nullable();
            $table->date('tanggal_pengeluaran');
            $table->decimal('jumlah', 18, 2);
            $table->string('status', 50)->default(''); // belum_dibayar, sudah_dibayar, sebagian
            $table->json('file_nota')->nullable();
            $table->json('file_buktitf')->nullable();
            $table->text('keterangan')->nullable();
            
            $table->string('user_created', 100)->default('');
            // Tambahan kolom untuk keterangan BOD ketika ditolak
            $table->text('catatan_bod')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_proyeks');
    }
};
