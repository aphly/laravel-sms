<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->bigInteger('lasttime')->unsigned();
            $table->timestamp('createtime')->default(DB::raw('CURRENT_TIMESTAMP'));
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
