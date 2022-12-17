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
        Schema::create('simulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticker_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('threshold');

            $table->integer('long_profit')->default(0);
            $table->integer('short_profit')->default(0);
            $table->integer('total_profit')->default(0);
            $table->integer('profit_percentage')->default(0);

            $table->integer('long_entered_days')->default(0);
            $table->integer('short_entered_days')->default(0);

            $table->integer('long_net_profit')->default(0);
            $table->integer('short_net_profit')->default(0);
            $table->integer('total_net_profit')->default(0);
            $table->integer('net_profit_percentage')->default(0);

            $table->timestamps();

            $table->unique(['ticker_id', 'threshold']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simulations');
    }
};
