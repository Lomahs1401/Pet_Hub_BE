<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingDoctorsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('rating_doctors', function (Blueprint $table) {
      $table->id();
      $table->integer('rating');
      $table->text('description');
      $table->text('reply')->nullable();
      $table->date('reply_date')->nullable();
      $table->foreignId('customer_id')->constrained('customers');
      $table->foreignId('doctor_id')->constrained('doctors');
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
    Schema::dropIfExists('rating_doctors');
  }
}
