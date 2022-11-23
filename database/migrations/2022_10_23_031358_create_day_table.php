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
        Schema::create('days', function (Blueprint $table) {
            $table->id();
            $table->integer('day_index')->index();
            $table->date('day')->index();
            $table->foreignId('ticker_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('simulation_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->text('long_enter_at_price')->nullable();
            $table->text('short_enter_at_price')->nullable();
            $table->text('long_exit_at_price')->nullable();
            $table->text('short_exit_at_price')->nullable();

            $table->foreignId('long_start_at_candle_stick_id')->nullable()->constrained('candle_sticks');
            $table->foreignId('short_start_at_candle_stick_id')->nullable()->constrained('candle_sticks');
            $table->foreignId('long_end_at_candle_stick_id')->nullable()->constrained('candle_sticks');
            $table->foreignId('short_end_at_candle_stick_id')->nullable()->constrained('candle_sticks');

            $table->float('long_profit')->default(0.00);
            $table->float('short_profit')->default(0.00);
            $table->float('total_profit')->default(0.00);

            $table->timestamps();

            $table->unique(['simulation_id', 'day_index']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('days');
    }
};
