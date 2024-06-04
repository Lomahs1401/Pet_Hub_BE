<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('customers', function (Blueprint $table) {
      $table->id();
      $table->string('full_name')->nullable();
      $table->string('gender')->nullable();
      $table->date('birthdate')->nullable();
      $table->string('address')->nullable();
      $table->string('CMND')->unique()->nullable();
      $table->string('phone')->unique()->nullable();
      $table->integer('ranking_point');
      $table->foreignId('account_id')->constrained('accounts');
      $table->foreignId('ranking_id')->nullable()->constrained('rankings');
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
    Schema::dropIfExists('customers');
  }
}
