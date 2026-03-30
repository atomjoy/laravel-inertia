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
		Schema::create('images', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('imageable_id');
			$table->string('imageable_type');
			$table->string('path');
			$table->unsignedTinyInteger('featured')->nullable()->default(0);
			$table->unsignedBigInteger('likes')->nullable()->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('images');
	}
};
