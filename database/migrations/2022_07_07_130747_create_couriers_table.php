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
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->boolean('active');
            $table->string('slug');
            $table->dateTime('last_location_at')->nullable();
            $table->decimal('lat',10,7)->nullable();
            $table->decimal('lng',10,7)->nullable();
            $table->boolean('using_app_pricing')->default(true);
            $table->decimal('base_price', 10, 2)->nullable();
            $table->decimal('base_distance', 10, 2)->nullable();
            $table->decimal('additional_distance_pricing', 10, 2)->nullable();
            $table->decimal('additional_stop_tax', 10, 2)->nullable();
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
        Schema::dropIfExists('couriers');
    }
};
