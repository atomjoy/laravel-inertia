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
		// S, M, Red, Blue, Wool, Cotton, Polyester
		Schema::create('properties', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('attribute_id');
			$table->string('name');
			$table->string('value')->nullable(); // Hex color
			$table->timestamps();
			$table->unique(['attribute_id', 'name']);
			$table->foreign('attribute_id')->references('id')->on('attributes')->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('properties');
	}
};
