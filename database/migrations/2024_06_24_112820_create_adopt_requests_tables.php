<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdoptRequestsTables extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('adopt_requests', function (Blueprint $table) {
      $table->id();
      $table->string('status');
      $table->text('note')->nullable();
      $table->foreignId('aid_center_id')->constrained('aid_centers');
      $table->foreignId('pet_id')->constrained('pets');
      $table->foreignId('customer_id')->constrained('customers');
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
    Schema::dropIfExists('adopt_requests');
  }
}
