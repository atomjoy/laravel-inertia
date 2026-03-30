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
		Schema::create('widgets', function (Blueprint $table) {
			$table->id();
			$table->string('title')->nullable();
			$table->string('url')->nullable();
			$table->string('website')->nullable()->default('youtube');
			$table->unsignedTinyInteger('is_visible')->nullable()->default(1);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('widgets');
	}
};
