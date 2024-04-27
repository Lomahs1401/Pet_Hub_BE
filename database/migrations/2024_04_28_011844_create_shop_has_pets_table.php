<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopHasPetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_has_pets', function (Blueprint $table) {
            $table->id();
            $table->boolean('status');
            $table->integer('quantity');
            $table->foreignId('shop_id')->constrained('shops');
            $table->foreignId('pet_id')->constrained('pets');
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
        Schema::dropIfExists('shop_has_pets');
    }
}
