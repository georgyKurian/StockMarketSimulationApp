<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candle_sticks', function (Blueprint $table) {
            $table->id();

            $table->integer('day_index')->index();
            $table->integer('time');

            $table->text('open');
            $table->text('high');
            $table->text('low');
            $table->text('close');
            $table->text('volume');
            $table->text('vw_avg_price')->nullable();

            $table->dateTime('recorded_at')->unique();

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
        Schema::dropIfExists('intradays');
    }
};
