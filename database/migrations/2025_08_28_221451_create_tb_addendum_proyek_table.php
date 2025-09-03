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
        Schema::create('tb_addendum_proyek', function (Blueprint $table) {
            $table->id('id_addendum');
            $table->unsignedBigInteger('id_proyek');

            $table->string('nomor_addendum', 100);
            $table->date('tanggal_addendum');

            $table->bigInteger('nilai_proyek_addendum')->default(0);   // tambahan kontrak
            $table->bigInteger('estimasi_hpp_addendum')->default(0);   // tambahan HPP
            $table->integer('tambahan_termin_addendum')->nullable();            // tambahan/ubah termin
            $table->integer('durasi_addendum')->nullable();            // tambahan durasi (bulan)

            $table->text('deskripsi_perubahan')->nullable();
            $table->json('attachment_file')->nullable();

            $table->timestamps();

            // Foreign key ke tb_proyek
            $table->foreign('id_proyek')
                ->references('id_proyek')->on('tb_proyek')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_addendum_proyek');
    }
};
