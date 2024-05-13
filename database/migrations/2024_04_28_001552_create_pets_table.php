<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type');
            $table->float('age')->nullable();
            $table->string('gender');
            $table->text('description');
            $table->double('price')->nullable();
            $table->string('image');
            $table->boolean('is_purebred');
            $table->boolean('is_adopt');
            $table->boolean('status');
            $table->foreignId('breed_id')->constrained('breeds');
            $table->foreignId('medical_center_id')->constrained('medical_centers');
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
        Schema::dropIfExists('pets');
    }
}
