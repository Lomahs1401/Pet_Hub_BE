<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('sub_orders', function (Blueprint $table) {
      $table->id();
      $table->double('sub_total_prices');
      $table->string('status');
      $table->foreignId('order_id')->constrained('orders');
      $table->foreignId('shop_id')->constrained('shops');
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
    Schema::dropIfExists('sub_orders');
  }
}
