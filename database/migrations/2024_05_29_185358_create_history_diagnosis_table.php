<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryDiagnosisTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('history_diagnosis', function (Blueprint $table) {
			$table->id();
			$table->text('reason');
			$table->text('diagnosis');
			$table->text('treatment');
			$table->text('health_condition');
			$table->text('note')->nullable();
			$table->foreignId('doctor_id')->constrained('doctors');
			$table->foreignId('pet_id')->constrained('pets');
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
		Schema::dropIfExists('history_diagnosis');
	}
}
