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
        Schema::create('data_iklans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_iklan_id')->constrained('jenis_iklans')->onDelete('cascade');
            $table->string('nama_iklan')->nullable();
            $table->string('kode_iklan')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
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
        Schema::dropIfExists('data_iklans');
    }
};
