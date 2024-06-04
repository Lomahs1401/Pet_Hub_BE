<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAidCentersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('aid_centers', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->string('image');
      $table->string('phone');
      $table->string('address');
      $table->string('website');
      $table->string('fanpage');
      $table->string('work_time');
      $table->string('establish_year');
      $table->string('certificate')->nullable();
      $table->foreignId('account_id')->constrained('accounts');
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
    Schema::dropIfExists('aid_centers');
  }
}
