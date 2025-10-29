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
        Schema::create('pack_itenaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('title', 255);
            $table->char('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('icon', 255)->nullable();

            // Foreign key: package
            $table->unsignedBigInteger('package_id')->index();
            $table->foreign('package_id')->references('id')->on('packages')->cascadeOnDelete();

            // Foreign key: pack_accomo_details
            $table->unsignedBigInteger('pack_accomo_details_id')->index();
            $table->foreign('pack_accomo_details_id')->references('id')->on('pack_accomo_details')->cascadeOnDelete();

            // Foreign key: meal
            $table->unsignedBigInteger('meal_id')->index();
            $table->foreign('meal_id')->references('id')->on('meals')->cascadeOnDelete();

            // Foreign key: activity
            $table->unsignedBigInteger('activity_id')->index();
            $table->foreign('activity_id')->references('id')->on('activities')->cascadeOnDelete();

            // Reference fields
            $table->uuid('package_uuid')->nullable();
            $table->string('package_title', 255)->nullable();
            $table->uuid('pack_accomo_details_uuid')->nullable();
            $table->string('pack_accomo_details_title', 255)->nullable();
            $table->uuid('meal_uuid')->nullable();
            $table->string('meal_title', 255)->nullable();
            $table->uuid('activity_uuid')->nullable();
            $table->string('activity_title', 255)->nullable();

            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('pack_itenaries', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropForeign(['pack_accomo_details_id']);
            $table->dropForeign(['meal_id']);
            $table->dropForeign(['activity_id']);
        });
        Schema::dropIfExists('pack_itenaries');
    }
};
