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
		Schema::table('users', function (Blueprint $table) {
			// Info
			$table->string('last_name', 50)->nullable();
			$table->string('location', 50)->nullable();
			$table->text('bio', 500)->nullable();
			$table->string('image')->nullable()->default('/default/avatar.webp');
			// Contact
			$table->integer('mobile_prefix')->nullable();
			$table->integer('mobile_number')->nullable();
			// Address
			$table->string('address_line_one', 50)->nullable();
			$table->string('address_line_two', 50)->nullable();
			$table->string('address_city', 50)->nullable();
			$table->string('address_state', 50)->nullable();
			$table->string('address_country', 50)->nullable();
			$table->string('address_postal', 50)->nullable();
			// Settings
			$table->tinyInteger('prefer_sms')->unsigned()->default(0);
			$table->tinyInteger('two_factor')->unsigned()->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('location');
			$table->dropColumn('bio');
			$table->dropColumn('image');
			$table->dropColumn('mobile_prefix');
			$table->dropColumn('mobile_number');
			$table->dropColumn('address_line_one');
			$table->dropColumn('address_line_two');
			$table->dropColumn('address_city');
			$table->dropColumn('address_state');
			$table->dropColumn('address_country');
			$table->dropColumn('address_postal');
			$table->dropColumn('two_factor');
			$table->dropColumn('prefer_sms');
		});
	}
};
