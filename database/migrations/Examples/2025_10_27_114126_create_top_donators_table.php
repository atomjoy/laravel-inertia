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
		Schema::create('top_donators', function (Blueprint $table) {
			$table->id();
			$table->unsignedTinyInteger('show')->nullable()->default(5);
			$table->unsignedTinyInteger('show_numbers')->nullable()->default(1);
			$table->enum('period', ['hour6', 'hour12', 'hour24', 'day', 'week', 'month', 'year'])->nullable()->default('day');
			$table->unsignedInteger('text_size')->nullable()->default(18);
			$table->unsignedInteger('text_weight')->nullable()->default(600);
			$table->string('text_font')->nullable()->default('Poppins');
			$table->string('text_align')->nullable()->default('left');
			$table->string('text_color')->nullable()->default('ff0033');
			$table->string('text_color_id')->nullable()->default('ffffff');
			$table->string('text_color_amount')->nullable()->default('ffffff');
			$table->string('animation')->nullable()->default('animate__none');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('top_donators');
	}
};
