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
		Schema::create('targets', function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->unsignedBigInteger('amount');
			$table->enum('period', ['hour6', 'hour12', 'hour24', 'day', 'week', 'month', 'year'])->nullable()->default('day');
			$table->string('bar_bg')->nullable()->default('2f2f2f');
			$table->string('bar_color')->nullable()->default('55cc55');
			$table->unsignedInteger('bar_opacity')->nullable()->default(100);
			$table->unsignedInteger('bar_radius')->nullable()->default(20);
			$table->unsignedInteger('bar_border')->nullable()->default(3);
			$table->string('animation')->nullable()->default('animate__none');
			$table->string('text_color')->nullable()->default('ffffff');
			$table->string('text_font')->nullable()->default('Poppins');
			$table->string('text_align')->nullable()->default('right');
			$table->unsignedInteger('text_size')->nullable()->default(18);
			$table->unsignedInteger('text_weight')->nullable()->default(600);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('targets');
	}
};
