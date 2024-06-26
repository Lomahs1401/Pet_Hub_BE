<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreedsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('breeds', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('type');
      $table->text('description');
      $table->string('image');
      $table->string('origin');
      $table->string('lifespan');
      $table->string('average_size');
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
    Schema::dropIfExists('breeds');
  }
}
