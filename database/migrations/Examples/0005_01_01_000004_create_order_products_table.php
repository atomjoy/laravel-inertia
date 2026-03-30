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
		 *  Brutto with tax included: amount_amount, discount_amount, packaging_amount, total_amount
		 */
		Schema::create('order_products', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('order_id');
			$table->string('name');
			$table->string('sku')->nullable();
			// Vat
			$table->decimal('vat')->nullable()->default(23);
			// Product
			$table->unsignedBigInteger('quantity')->nullable()->default(1);
			// Cena jednostkowa przed rabatem netto (100PLN)
			$table->unsignedBigInteger('unit_net_price_before_discount')->nullable()->default(0);
			// Discount amount netto (10PLN)
			$table->unsignedBigInteger('unit_net_discount_price')->nullable()->default(0);
			// Cena jednostkowa po rabacie netto (90PLN)
			$table->unsignedBigInteger('unit_net_price')->nullable()->default(0);
			// Wartość netto productów
			$table->unsignedBigInteger('net_price')->nullable()->default(0);
			// Watorść vat produktów
			$table->unsignedBigInteger('tax_price')->nullable()->default(0);
			// Wartość brutto productów po rabatach
			$table->unsignedBigInteger('gross_price')->nullable()->default(0);
			// Rodzaj produktu
			$table->enum('kind', ['regular', 'addon', 'delivery', 'packaging'])->nullable()->default('regular');
			// Time
			$table->timestamps();
			$table->softDeletes();
			$table->foreign('order_id')->references('id')->on('orders')->cascadeOnUpdate();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('order_products');
	}
};
