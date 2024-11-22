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
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('mobile')->unique();
            $table->string('otp')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('name')->nullable();
            $table->string('pan_number')->nullable()->unique();
            $table->string('aadhar_number')->nullable()->unique();
            $table->string('license_number')->nullable()->unique();
            $table->boolean('is_verified')->default(false);
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
        Schema::dropIfExists('otps');
    }
};
