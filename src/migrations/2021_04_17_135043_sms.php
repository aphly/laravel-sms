<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Sms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->string('phone',20)->primary();
            $table->string('smscode',10);
            $table->timestamp('expiretime')->nullable();
            $table->integer('total')->unsigned();
            $table->integer('times')->unsigned();
            $table->timestamp('lasttime')->nullable()->index();
            $table->timestamp('createtime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms');
    }
}
