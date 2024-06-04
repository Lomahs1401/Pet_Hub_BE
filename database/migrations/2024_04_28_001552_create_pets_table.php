<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('pets', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('type');
      $table->float('age')->nullable();
      $table->string('gender');
      $table->text('description')->nullable();
      $table->string('image')->nullable();
      $table->boolean('is_purebred');
      $table->boolean('status')->nullable();
      $table->foreignId('breed_id')->constrained('breeds');
      $table->foreignId('aid_center_id')->nullable()->constrained('aid_centers');
      $table->foreignId('customer_id')->nullable()->constrained('customers');
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
    Schema::dropIfExists('pets');
  }
}
