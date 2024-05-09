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
        Schema::create('sms_driver', function (Blueprint $table) {
            $table->id();
            $table->string('name',16);
            $table->string('key_id',255);
            $table->string('key_secret',255);
            $table->string('sdk_app_id',255)->nullable();
            $table->string('sdk_app_key',255)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('created_at');
            $table->unsignedBigInteger('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_driver');
    }
};
