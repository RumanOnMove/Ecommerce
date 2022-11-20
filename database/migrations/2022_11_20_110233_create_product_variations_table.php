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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('color_id')->nullable();
            $table->unsignedBigInteger('size_id')->nullable();
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('color_id')->references('id')->on('colors');
            $table->foreign('size_id')->references('id')->on('sizes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variations');
    }
};
