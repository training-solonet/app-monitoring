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
        Schema::create('tb_siswa_rpl', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', ['learning', 'project']);
            $table->string('report');
            $table->date('waktu_mulai');
            $table->date('waktu_selesai');        
            $table->enum('status', ['to do', 'doing','done']);
            $table->foreignId('id_siswa')->constrained('siswa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_siswa_rpl');
    }
};
