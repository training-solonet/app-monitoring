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
        Schema::create('tb_siswa_tkj', function (Blueprint $table) {
            $table->id();
            $table->string('report');
            $table->date('waktu_mulai');
            $table->date('waktu_selesai');        
            $table->enum('status', ['to do', 'doing','done']);
            $table->enum('kategori', ['stand by', 'keluar']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_siswa_tkj');
    }
};
