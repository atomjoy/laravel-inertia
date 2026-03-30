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
		Schema::create('deliveries', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('net_price')->nullable()->default(0);
			$table->unsignedBigInteger('tax_price')->nullable()->default(0);
			$table->unsignedBigInteger('gross_price')->nullable()->default(0);
			$table->string('service')->nullable()->default('post_office');
			$table->string('service_id')->nullable();
			$table->string('tracking_number')->nullable();
			$table->string('tracking_url')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('deliveries');
	}
};
