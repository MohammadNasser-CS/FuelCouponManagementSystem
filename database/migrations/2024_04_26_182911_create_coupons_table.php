<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigInteger('id')->unique()->primary();
            $table->enum('vehicle_number', ['45958515', '45958514', '45958513']);
            $table->dateTime('filling_datetime')->default(now());
            $table->float('Car_currently_tank')->nullable();
            $table->float('Coupon_fuel_quantity');
            $table->float('Global_fuel_price');
            $table->float('Coupon_fuel_quantity_price');
            $table->enum('currency', ['Dollar', 'Euro', 'Dinar', 'NIS']);
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('Employee_id');
            $table->enum('Fuel_station_name', ['StationOne', 'StationTwo', 'StationThree']);
            $table->string('Coupon_pdf_path')->nullable();
            $table->enum('status', ['paid', 'unpaid', 'cancelled'])->default('unpaid');
            $table->string('Reigon')->nullable();
            $table->string('City')->nullable();

            $table->foreign('Employee_id')->references('id')->on('users');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
