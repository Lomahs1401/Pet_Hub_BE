<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingShopInteractsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('rating_shop_interacts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('rating_shop_id')->constrained('rating_shops');
      $table->foreignId('account_id')->constrained('accounts');
      $table->timestamps();
      $table->softDeletes();
      $table->unique(['rating_shop_id', 'account_id']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('rating_shop_interacts');
  }
}
