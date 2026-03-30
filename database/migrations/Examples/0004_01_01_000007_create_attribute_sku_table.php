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
		// Pivot table product variants and attributes
		Schema::create('attribute_sku', function (Blueprint $table) {
			$table->id('id');
			$table->foreignId('sku_id')->constrained('skus')->onUpdate('cascade')->onDelete('cascade');
			$table->foreignId('attribute_id')->constrained('attributes')->onUpdate('cascade')->onDelete('cascade');
			$table->foreignId('property_id')->constrained('properties')->onUpdate('cascade')->onDelete('cascade');
			$table->timestamps();
			$table->unique(['attribute_id', 'sku_id', 'property_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('attribute_sku');
	}
};
