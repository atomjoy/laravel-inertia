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
		// Product variants
		Schema::create('skus', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('product_id');
			$table->string('sku')->unique();
			$table->string('slug')->unique();
			$table->string('name')->unique();
			$table->string('description')->nullable();
			$table->text('long_description')->nullable();
			// Vat
			$table->decimal('vat')->nullable()->default(23);
			// Price
			$table->unsignedBigInteger('net_price')->nullable()->default(0);
			$table->unsignedBigInteger('tax_price')->nullable()->default(0);
			$table->unsignedBigInteger('gross_price')->nullable()->default(0);
			// Sale price
			$table->unsignedBigInteger('net_sale_price')->nullable()->default(0);
			$table->unsignedBigInteger('tax_sale_price')->nullable()->default(0);
			$table->unsignedBigInteger('gross_sale_price')->nullable()->default(0);
			// Availability
			$table->unsignedBigInteger('stock_quantity')->nullable();
			$table->unsignedTinyInteger('on_stock')->nullable()->default(1);
			$table->unsignedTinyInteger('on_sale')->nullable()->default(0);
			$table->unsignedBigInteger('views')->nullable()->default(0);
			$table->unsignedBigInteger('sorting')->nullable()->default(0);
			// Virtual product (Course, Membership, Subscription)
			$table->nullableMorphs('virtuable');
			// Time
			$table->timestamps();
			$table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('skus');
	}
};
