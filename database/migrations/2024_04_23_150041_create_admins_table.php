<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('admins', function (Blueprint $table) {
      $table->id();
      $table->string('full_name');
      $table->date('birthdate');
      $table->string('gender');
      $table->string('CMND')->unique();
      $table->string('address');
      $table->string('phone')->unique();
      $table->string('image')->nullable();
      $table->boolean('status');
      $table->foreignId('account_id')->constrained('accounts');
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
    Schema::dropIfExists('admins');
  }
}
