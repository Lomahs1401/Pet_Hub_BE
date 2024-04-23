<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('gender');
            $table->date('birthdate');
            $table->string('CMND')->unique();
            $table->string('address');
            $table->string('phone')->unique();
            $table->string('account_bank');
            $table->string('name_bank');
            $table->dateTime('day_start');
            $table->dateTime('day_quit')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status');
            $table->foreignId('account_id')->nullable()->constrained('accounts');
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
        Schema::dropIfExists('staffs');
    }
}
