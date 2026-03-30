<?php

use App\Enums\Payments\CurrencyEnum;
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
		Schema::create('donations', function (Blueprint $table) {
			$table->id();
			$table->string('name'); // Client name
			$table->string('email'); // Client email
			$table->string('message'); // Client message
			$table->string('phone')->nullable(); // Client phone
			$table->string('last_name')->nullable(); // Client last name
			$table->unsignedBigInteger('gif')->nullable(); // Client gif image id
			$table->unsignedBigInteger('amount')->nullable()->default(0);
			$table->enum('currency', [...CurrencyEnum::cases()])->nullable()->default(CurrencyEnum::PLN->value);
			$table->enum('gateway', [...PaymentGatewaysEnum::cases()])->nullable()->default(PaymentGatewaysEnum::PAYU->value);
			$table->enum('status', [...PaymentStatusEnum::cases()])->nullable()->default(PaymentStatusEnum::NEW->value);
			$table->string('payment_id')->nullable()->unique(); // Transaction id must be unique
			$table->string('external_id')->nullable()->unique(); // Payment gateway transaction id
			$table->unsignedTinyInteger('is_seen')->nullable()->default(0);
			$table->text('url')->nullable();
			$table->string('ip')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('donations');
	}
};
