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
		/**
		 * Brutto with tax included: unit_price, packaging_price
		 */
		Schema::create('products', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('slug')->unique()->nullable();
			$table->string('sku')->unique()->nullable();
			$table->string('description')->nullable();
			$table->text('long_description')->nullable();
			$table->unsignedTinyInteger('on_stock')->nullable()->default(0);
			$table->unsignedTinyInteger('on_downloadable')->nullable()->default(0);
			$table->unsignedTinyInteger('on_virtual')->nullable()->default(0);
			$table->unsignedBigInteger('views')->nullable()->default(0);
			$table->unsignedBigInteger('sorting')->nullable()->default(0);
			$table->decimal('rating')->nullable()->default(5.0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('products');
	}
};
