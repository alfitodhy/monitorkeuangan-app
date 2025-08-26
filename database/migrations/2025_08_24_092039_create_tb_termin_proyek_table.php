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
        Schema::create('tb_termin_proyek', function (Blueprint $table) {
            $table->id('id_termin');
            $table->unsignedBigInteger('id_proyek');
            $table->integer('termin_ke');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->decimal('jumlah', 18, 2)->default(0);
            $table->string('status_pembayaran', 50)->default('belum dibayar');
            $table->date('tanggal_lunas')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps(); // otomatis bikin created_at & updated_at

            // foreign key
            $table->foreign('id_proyek')
                ->references('id_proyek')
                ->on('tb_proyek')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_termin_proyek');
    }
};
