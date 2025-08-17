<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_proyek', function (Blueprint $table) {
            $table->id('id_proyek');
            $table->string('kode_proyek', 50)->unique();
            $table->string('nama_proyek');
            $table->string('nama_klien');
            $table->text('attachment_file')->nullable(); // bisa null kalau tidak ada file
            $table->string('status_proyek', 50)->default('');
            $table->decimal('nilai_proyek', 18, 2);
            $table->integer('termin')->default(1);
            $table->decimal('estimasi_hpp', 18, 2)->default(0);
            $table->string('tipe_proyek', 50);
            $table->date('tanggal_start_proyek')->nullable();
            $table->date('tanggal_deadline')->nullable();
            $table->integer('durasi_pengerjaan_bulan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('is_active', 1)->default('Y');
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_proyek');
    }
};
