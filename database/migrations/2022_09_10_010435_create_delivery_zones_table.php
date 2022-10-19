<?php

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
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->enum('type', ['Recojo y entrega', 'Solo recojo', 'Solo entrega']);

            $table->integer('price_pick_up')->default('0');
            $table->decimal('price_drop_off')->default('0');
            $table->string('currency')->default('PEN');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_zones');
    }
};
