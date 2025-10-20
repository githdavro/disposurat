<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('surats', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_surat')->nullable();
        $table->string('nomor_agenda')->nullable();
        $table->string('perihal');
        $table->text('isi');
        $table->enum('asal', ['internal', 'eksternal']);
        $table->foreignId('pengirim_id')->constrained('users');
        $table->foreignId('tujuan_unit_id')->constrained('units');
        $table->foreignId('asal_unit_id')->nullable()->constrained('units'); // Pastikan ada
        $table->string('file_path')->nullable();
        $table->decimal('nilai', 15, 2)->nullable();
        $table->enum('status', [
            'draft', 'dikirim', 'diterima_pengadaan', 
            'diterima_direktur', 'disetujui', 'ditolak', 'diarsipkan'
        ])->default('draft');
        $table->enum('tipe_surat', ['masuk', 'keluar'])->default('masuk');
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};