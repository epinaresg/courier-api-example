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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('shipment_id');
            $table->foreign('shipment_id')->references('id')->on('shipments');
            $table->index('shipment_id');

            $table->integer('order')->default(0);
            $table->index('order');

            $table->enum('type', ['pickup', 'dropoff']);

            $table->date('date');

            $table->string('package_content');
            $table->string('package_instruction')->nullable();

            $table->string('address');
            $table->string('address_reference')->nullable();

            $table->foreignUuid('delivery_zone_id');
            $table->foreign('delivery_zone_id')->references('id')->on('delivery_zones');
            $table->index('delivery_zone_id');

            $table->string('contact_full_name');
            $table->string('contact_phone_code');
            $table->string('contact_phone_number');

            $table->string('contact_email')->nullable();

            $table->foreignUuid('payment_method_id');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->index('payment_method_id');

            $table->foreignUuid('state_id');
            $table->foreign('state_id')->references('id')->on('states');
            $table->index('state_id');

            $table->decimal('total_receivable', 10, 2)->default('0.00');

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
        Schema::dropIfExists('tasks');
    }
};
