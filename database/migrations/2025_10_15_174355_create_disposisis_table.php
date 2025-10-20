<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disposisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained()->onDelete('cascade');
            $table->foreignId('dari_unit_id')->constrained('units');
            $table->foreignId('tujuan_unit_id')->constrained('units');
            $table->text('catatan')->nullable();
            $table->enum('status', ['dikirim', 'diterima', 'diproses', 'selesai'])->default('dikirim');
            $table->timestamp('tanggal_disposisi')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposisis');
    }
};