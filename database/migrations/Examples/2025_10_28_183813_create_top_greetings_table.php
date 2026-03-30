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
		Schema::create('top_greetings', function (Blueprint $table) {
			$table->id();
			$table->unsignedTinyInteger('style')->nullable()->default(1);
			$table->string('audio')->nullable()->default('money');
			// Bg
			$table->string('bg')->nullable()->default('55cc55aa');
			$table->string('border')->nullable()->default('ffffff');
			$table->unsignedInteger('radius')->nullable()->default(10);
			// Name
			$table->string('font1')->nullable()->default('Poppins');
			$table->string('color1')->nullable()->default('ff0033');
			$table->unsignedInteger('size1')->nullable()->default(18);
			$table->unsignedInteger('weight1')->nullable()->default(700);
			$table->string('animation1')->nullable()->default('animate__none');
			// Amount
			$table->string('font2')->nullable()->default('Poppins');
			$table->string('color2')->nullable()->default('ff0033');
			$table->unsignedInteger('size2')->nullable()->default(18);
			$table->unsignedInteger('weight2')->nullable()->default(700);
			$table->string('animation2')->nullable()->default('animate__none');
			// Msg
			$table->string('font3')->nullable()->default('Poppins');
			$table->string('color3')->nullable()->default('ff0033');
			$table->unsignedInteger('size3')->nullable()->default(16);
			$table->unsignedInteger('weight3')->nullable()->default(500);
			$table->string('animation3')->nullable()->default('animate__none');
			// All
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('top_greetings');
	}
};
