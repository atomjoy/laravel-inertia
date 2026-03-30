<?php

use App\Enums\Payments\CurrencyEnum;
use App\Enums\Payments\OrderStatusEnum;
use App\Enums\Payments\PaymentGatewaysEnum;
use App\Enums\Payments\PaymentMethodsEnum;
use App\Enums\Payments\ShippingMethodsEnum;
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
		 * Brutto with tax included: products_amount, shipping_amount, packaging_amount, total_amount
		 */
		Schema::create('orders', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('user_id')->nullable();
			$table->unsignedBigInteger('refund_id')->nullable();
			// Cost
			$table->unsignedBigInteger('net_amount')->nullable();
			$table->unsignedBigInteger('tax_amount')->nullable();
			$table->unsignedBigInteger('gross_amount')->nullable();
			// Settings
			$table->enum('status', [...OrderStatusEnum::cases()])->default(OrderStatusEnum::DRAFT);
			$table->enum('currency', [...CurrencyEnum::cases()])->nullable()->default(CurrencyEnum::PLN);
			$table->enum('shipping_method', [...ShippingMethodsEnum::cases()])->nullable()->default(ShippingMethodsEnum::DELIVERY);
			$table->enum('payment_method', [...PaymentMethodsEnum::cases()])->nullable()->default(PaymentMethodsEnum::TRANSFER);
			$table->enum('payment_gateway', [...PaymentGatewaysEnum::cases()])->nullable();
			// Customer details (required for payment)
			$table->string('customer_first_name');
			$table->string('customer_last_name');
			$table->string('customer_mobile');
			$table->string('customer_email');
			// Settings
			$table->unsignedTinyInteger('is_same_as_shipping')->nullable()->default(1);
			$table->unsignedTinyInteger('is_invoice_required')->nullable()->default(0);
			$table->unsignedTinyInteger('is_company')->nullable()->default(0);
			// Shipping address
			$table->string('shipping_line_one', 50)->nullable();
			$table->string('shipping_line_two', 50)->nullable();
			$table->string('shipping_city', 50)->nullable();
			$table->string('shipping_state', 50)->nullable();
			$table->string('shipping_postal', 50)->nullable();
			$table->string('shipping_country', 50)->nullable();
			// Billing address
			$table->string('billing_line_one', 50)->nullable();
			$table->string('billing_line_two', 50)->nullable();
			$table->string('billing_city', 50)->nullable();
			$table->string('billing_state', 50)->nullable();
			$table->string('billing_postal', 50)->nullable();
			$table->string('billing_country', 50)->nullable();
			// Billing company
			$table->string('billing_company')->nullable();
			$table->string('billing_nip', 50)->nullable();
			// Delivery
			$table->enum('delivery_service', ['local', 'external'])->nullable()->default('local');
			$table->unsignedBigInteger('delivery_id')->nullable();
			// Pickup
			$table->unsignedBigInteger('pickup_on_site')->nullable();
			$table->dateTime('pickup_on_site_at')->nullable();
			// Time
			$table->timestamps();
			$table->softDeletes();
			// Keys
			$table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
			$table->foreign('delivery_id')->references('id')->on('deliveries')->cascadeOnUpdate()->cascadeOnDelete();
			$table->foreign('refund_id')->references('id')->on('refunds')->cascadeOnUpdate()->cascadeOnDelete();
			// For single transaction and move transactions migration above orders
			// $table->unsignedBigInteger('transaction_id')->nullable();
			// $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnUpdate()->cascadeOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('orders');
	}
};
