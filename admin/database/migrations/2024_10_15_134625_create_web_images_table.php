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
    Schema::create('web_images', function (Blueprint $table) {
        $table->id();
        $table->string('image_1_path')->nullable();
        $table->integer('image_1_width')->nullable();
        $table->integer('image_1_height')->nullable();

        $table->string('image_2_path')->nullable();
        $table->integer('image_2_width')->nullable();
        $table->integer('image_2_height')->nullable();

        $table->string('image_3_path')->nullable();
        $table->integer('image_3_width')->nullable();
        $table->integer('image_3_height')->nullable();

        $table->string('image_4_path')->nullable();
        $table->integer('image_4_width')->nullable();
        $table->integer('image_4_height')->nullable();

        $table->string('image_5_path')->nullable();
        $table->integer('image_5_width')->nullable();
        $table->integer('image_5_height')->nullable();

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
        Schema::dropIfExists('web_images');
    }
};
