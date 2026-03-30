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
		Schema::create('top_timers', function (Blueprint $table) {
			$table->id();
			$table->timestamp('endtime')->nullable()->default(now());
			$table->unsignedInteger('text_size')->nullable()->default(18);
			$table->unsignedInteger('text_weight')->nullable()->default(600);
			$table->string('text_color')->nullable()->default('ffffff');
			$table->string('text_font')->nullable()->default('Poppins');
			$table->string('animation')->nullable()->default('animate__none');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('top_timers');
	}
};
