<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPemasukanProyekTable extends Migration
{
    public function up()
    {
        Schema::create('tb_pemasukan_proyek', function (Blueprint $table) {
            $table->id('id_pemasukan');
            $table->unsignedBigInteger('id_proyek');
            $table->unsignedBigInteger('id_termin')->nullable();
            $table->date('tanggal_pemasukan');
            $table->string('nama_klien', 100)->nullable();
            $table->string('termin_ke', 20)->nullable();
            $table->decimal('jumlah', 18, 2);
            $table->string('metode_pembayaran', 50)->nullable();
            $table->json('attachment_file')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_proyek')
                ->references('id_proyek')->on('tb_proyek')
                ->onDelete('cascade')
                ->onUpdate('cascade');


        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_pemasukan_proyek');
    }
}
