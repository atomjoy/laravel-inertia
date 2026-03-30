<?php

use App\Enums\Payments\PaymentGatewaysEnum;
use App\Enums\Payments\PaymentStatusEnum;
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
		 *  Order id from gateway: WZHF5FFDRJ140731GUEST000P01 needed for refunds
		 */
		Schema::create('transactions', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('order_id');
			$table->uuid('payment_id')->unique(); // Our transaction id must be unique
			$table->string('external_id')->unique(); // From payment gateway
			$table->enum('gateway', [...PaymentGatewaysEnum::cases()])->nullable()->default('payu');
			$table->enum('status', [...PaymentStatusEnum::cases()])->nullable()->default('new');
			$table->unsignedBigInteger('amount')->nullable()->default(0);
			$table->string('currency', 3)->nullable()->default('PLN');
			$table->text('url')->nullable();
			$table->string('ip')->nullable();
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
		Schema::dropIfExists('transactions');
	}
};
