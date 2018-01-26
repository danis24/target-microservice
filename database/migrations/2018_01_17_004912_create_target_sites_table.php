<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email');
            $table->string('ip')->nullable();
            $table->string('url');
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('countryCode')->nullable();
            $table->string('isp')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('org')->nullable();
            $table->string('regionName')->nullable();
            $table->string('timeZone')->nullable();
            $table->string('zip')->nullable();
            $table->string('tokenSite')->nullable();
            $table->enum('status', ['not_verified', 'verified']);
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE `targets` CHANGE `id` `id` BINARY(16)  NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('targets');
    }
}
