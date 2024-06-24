<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('expo_tokens', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('account_id');
      $table->string('expo_token');
      $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('expo_tokens');
  }
};
