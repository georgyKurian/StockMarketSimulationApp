<?php

use App\Models\Intraday;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
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

            $table->foreignId('start_at_intraday_id')->constrained('intradays');
            $table->text('enter_at_price');
            $table->text('exit_at_price');
            $table->foreignId('end_at_intraday_id')->constrained('intradays')->nullable();
            $table->float('enter_price')->nullable();
            $table->float('exit_price')->nullable();
            $table->float('profit')->default(0.00);
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
        Schema::dropIfExists('days');
    }
};
