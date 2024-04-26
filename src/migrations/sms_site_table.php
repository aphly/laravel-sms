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
        Schema::create('sms_site', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id')->index();
            $table->string('app_key',64)->nullable();
            $table->string('host',64)->index();
            $table->unsignedBigInteger('template_id')->index();
            $table->tinyInteger('type')->default(0);
            $table->unsignedTinyInteger('ip_limit')->default(20);
            $table->unsignedTinyInteger('phone_limit')->default(10);
            $table->unsignedTinyInteger('expire')->default(2);
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('created_at');
            $table->unsignedBigInteger('updated_at');
            //$table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_site');
    }
};
