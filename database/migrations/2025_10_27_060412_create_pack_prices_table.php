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
        Schema::create('pack_price', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('title', 255);
            $table->char('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('icon', 255)->nullable();

            // Foreign key: package
            $table->unsignedBigInteger('package_id');
            $table->foreign('package_id')->references('id')->on('packages')->cascadeOnDelete();

            // Package reference
            $table->uuid('package_uuid')->nullable();
            $table->string('package_title', 255)->nullable();

            // Foreign key: currency
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->nullOnDelete();

            // Currency reference fields
            $table->uuid('currency_uuid')->nullable();
            $table->string('currency_title', 255)->nullable();

            // JSON field
            $table->json('air_ticket_details')->nullable();

            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('pack_price', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropForeign(['currency_id']);
        });
        Schema::dropIfExists('pack_price');
    }
};
