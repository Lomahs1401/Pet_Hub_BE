<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('quantity');
            $table->double('amount');
            $table->double('price');
            $table->foreignId('cart_id')->constrained('carts');
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('service_id')->nullable()->constrained('services');
            $table->foreignId('pet_id')->nullable()->constrained('pets');
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
        Schema::dropIfExists('cart_items');
    }
}
