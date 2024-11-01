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
            $table->enum('kategori',['DiKantor', 'Keluar Dengan Teknisi','Learning','Project']);
            $table->text('report');
            $table->datetime('waktu_mulai')->nullable();
            $table->datetime('waktu_selesai')->nullable(); 
            $table->enum('status', ['to do','doing','done'])->nullable();
            $table->timestamps();
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