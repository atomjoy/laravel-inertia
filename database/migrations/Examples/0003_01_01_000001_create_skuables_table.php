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
		// Skus virtual products models:
		// Course, Subscription, Membership, Keys
		Schema::create('skuables', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('sku_id'); // Sku::id
			$table->unsignedBigInteger('skuable_id'); // Connected Model::id
			$table->string('skuable_type'); // Connected Model::class
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('skuables');
	}
};
