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
            $table->float('long_profit')->default(0.00);
            $table->float('short_profit')->default(0.00);
            $table->float('total_profit')->default(0.00);
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
