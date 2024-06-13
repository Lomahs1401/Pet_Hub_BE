<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('accounts', function (Blueprint $table) {
      $table->id();
      $table->string('username', 50);
      $table->string('email')->unique();
      $table->string('password');
      $table->string('avatar')->nullable();
      $table->boolean('enabled');
      $table->foreignId('role_id')->constrained('roles');
      $table->string('reset_code')->nullable();
      $table->timestamp('reset_code_expires_at')->nullable();
      $table->string('reset_code_attempts')->nullable();
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
    Schema::dropIfExists('accounts');
  }
}
