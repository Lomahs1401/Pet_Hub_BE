<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('appointments', function (Blueprint $table) {
      $table->id();
      $table->text('message')->nullable();
      $table->dateTime('start_time');
      $table->boolean('done')->default(false);
      $table->foreignId('customer_id')->constrained('customers');
      $table->foreignId('doctor_id')->constrained('doctors');
      $table->foreignId('pet_id')->constrained('pets');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('appointments');
  }
}
