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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('email')->unique();
            $table->index('email');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('password')->nullable();


            $table->string('business_name')->nullable();
            $table->string('business_number')->nullable();
            $table->string('business_address')->nullable();
            $table->string('business_phone_code')->nullable();
            $table->string('business_phone_number')->nullable();
            $table->string('business_email')->nullable();

            $table->string('contact_first_name')->nullable();
            $table->string('contact_last_name')->nullable();
            $table->string('contact_phone_code')->nullable();
            $table->string('contact_phone_number')->nullable();
            $table->string('contact_email')->nullable();

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
        Schema::dropIfExists('customers');
    }
};
