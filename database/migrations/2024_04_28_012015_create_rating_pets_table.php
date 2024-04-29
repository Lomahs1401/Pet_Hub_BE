<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingPetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating_pets', function (Blueprint $table) {
            $table->id();
            $table->integer('rating');
            $table->text('description');
            $table->foreignId('customer_id')->constrained('customers');
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
        Schema::dropIfExists('rating_pets');
    }
}
