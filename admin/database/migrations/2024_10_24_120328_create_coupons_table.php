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
    Schema::create('coupons', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();  // Unique coupon code
        $table->enum('type', ['fixed', 'percent']);  // Type of coupon
        $table->decimal('value', 8, 2);  // Coupon value, either fixed amount or percentage
        $table->decimal('minimum_amount', 8, 2)->nullable();  // Optional minimum order amount to apply coupon
        $table->integer('max_uses')->default(1);  // Max number of times the coupon can be used
        $table->date('expiry_date')->nullable();  // Expiry date for the coupon
        $table->boolean('is_active')->default(true);  // If the coupon is currently active
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
        Schema::dropIfExists('coupons');
    }
};
