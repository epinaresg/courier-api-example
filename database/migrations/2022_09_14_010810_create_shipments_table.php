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
        Schema::create('shipments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->index('customer_id');

            $table->foreignUuid('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->index('vehicle_id');

            $table->integer('tasks_qty')->default('0');
            $table->integer('tasks_missing_qty')->default('0');
            $table->integer('tasks_completed_qty')->default('0');
            $table->decimal('tasks_completed_percent')->default('0.00');

            $table->decimal('total_receivable', 10, 2)->default('0.00');
            $table->decimal('total_pick_up', 10, 2)->default('0.00');
            $table->decimal('total_drop_off', 10, 2)->default('0.00');
            $table->decimal('total', 10, 2)->default('0.00');

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
        Schema::dropIfExists('shipments');
    }
};
