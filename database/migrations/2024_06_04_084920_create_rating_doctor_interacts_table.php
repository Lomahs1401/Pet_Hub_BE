<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingDoctorInteractsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('rating_doctor_interacts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('rating_doctor_id')->constrained('rating_doctors');
      $table->foreignId('account_id')->constrained('accounts');
      $table->timestamps();
      $table->softDeletes();
      $table->unique(['rating_doctor_id', 'account_id']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('rating_doctor_interacts');
  }
}
