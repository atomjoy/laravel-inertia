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
		Schema::create('refunds', function (Blueprint $table) {
			$table->id();
			$table->string('refund_id')->unique(); // refundId
			$table->string('external_id'); // extRefundId
			$table->enum('status', ['pending', 'canceled', 'finalized'])->nullable();
			$table->unsignedBigInteger('amount')->nullable()->default(0);
			$table->string('currency', 3)->nullable()->default('PLN');
			$table->string('description')->nullable();
			$table->timestamps();
			$table->softDeletes();
			// For multiple refunds and move this migration after orders !!!
			// $table->unsignedBigInteger('order_id')->nullable();
			// $table->foreign('order_id')->references('id')->on('orders')->cascadeOnUpdate()->cascadeOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('refunds');
	}
};
