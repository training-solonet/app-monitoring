<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('aktivitas_id')->nullable();
            $table->enum('kategori', ['DiKantor', 'Keluar Dengan Teknisi', 'Belajar', 'Projek']);
            $table->text('report')->nullable();
            $table->datetime('waktu_mulai')->nullable();
            $table->datetime('waktu_selesai')->nullable();
            $table->enum('status', ['Mulai', 'Sedang Berlangsung', 'Selesai'])->nullable();
            $table->enum('report_status', ['Sudah Lapor', 'Belum Lapor'])->nullable();
            $table->unsignedBigInteger('materi_id')->nullable();
            $table->text('bukti')->nullable();
            $table->timestamps();

            $table->foreign('materi_id')->references('id')->on('materi');
            $table->foreign('aktivitas_id')->references('id')->on('aktivitas');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siswa');
    }
};
