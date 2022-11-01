<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('courier_id');
            $table->string('pickup_location');
            $table->text('pickup_location_data');
            $table->boolean('save_pickup_location_for_next_order');
            $table->text('delivery_locations_data');
            $table->boolean('need_return_to_pickup_location');
            $table->decimal('distance',10,3);
            $table->decimal('courier_value',10,2);
            $table->decimal('app_value',10,2);
            $table->decimal('total_value',10,2);
            $table->text('customer_observation')->nullable();
            $table->unsignedInteger('offline_payment_method_id'); //0 is this is online
            $table->string('payment_gateway')->nullable();
            $table->string('gateway_id')->nullable();
            $table->enum('payment_status',['pending','paid','cancelled'])->default('pending');
            $table->dateTime('payment_status_date');
            $table->enum('order_status',['waiting','pending','accepted','rejected','collected','delivered','completed','cancelled'])->default('pending'); //waiting is only for online payments
            $table->dateTime('order_status_date');
            $table->text('status_observation')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
