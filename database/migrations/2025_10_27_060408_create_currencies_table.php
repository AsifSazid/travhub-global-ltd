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
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('title', 255);
            $table->char('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('icon', 255)->nullable();
            $table->string('currency_code', 255)->nullable();

            // Foreign Key: country_id
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');

            // Extra reference fields
            $table->uuid('country_uuid')->nullable();
            $table->string('country_title', 255)->nullable();

            // Foreign Key: currency_rate_id
            $table->unsignedBigInteger('current_rate_id')->nullable();
            $table->foreign('current_rate_id')->references('id')->on('current_rates')->onDelete('set null');

            // Extra reference fields
            $table->uuid('current_rate_uuid')->nullable();
            $table->string('current_rate_title', 255)->nullable();

            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['current_rate_id']);
        });
        Schema::dropIfExists('currencies');
    }
};
