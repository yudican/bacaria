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
        Schema::table('data_iklans', function (Blueprint $table) {
            $table->string('ads_slot_id')->nullable()->after('link');
            $table->string('ads_client_id')->nullable()->after('ads_slot_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_iklans', function (Blueprint $table) {
            $table->dropColumn('ads_slot_id');
            $table->dropColumn('ads_client_id');
        });
    }
};
