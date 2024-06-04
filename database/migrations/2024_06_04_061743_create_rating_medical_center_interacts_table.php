<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingMedicalCenterInteractsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('rating_medical_center_interacts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('rating_medical_center_id')->constrained('rating_medical_centers');
      $table->foreignId('account_id')->constrained('accounts');
      $table->timestamps();
      $table->softDeletes();
      $table->unique(['rating_medical_center_id', 'account_id'], 'rating_medical_center_interacts_index_unique');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('rating_medical_center_interacts');
  }
}
