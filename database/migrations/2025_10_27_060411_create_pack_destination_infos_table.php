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
        Schema::create('pack_destination_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('title', 255);
            $table->char('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('icon', 255)->nullable();

            // Foreign Key: package (required)
            $table->unsignedBigInteger('package_id');
            $table->foreign('package_id')->references('id')->on('packages')->cascadeOnDelete();

            // package reference fields
            $table->uuid('package_uuid')->nullable();
            $table->string('package_title', 255)->nullable();

            // Foreign Key: country (required)
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete();

            // country reference fields
            $table->uuid('country_uuid')->nullable();
            $table->string('country_title', 255)->nullable();

            // JSON field: cities
            $table->json('cities')->nullable();

            // Foreign Key: activity (required)
            $table->unsignedBigInteger('activity_id');
            $table->foreign('activity_id')->references('id')->on('activities')->cascadeOnDelete();

            // activity reference fields
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
        Schema::table('pack_destination_infos', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropForeign(['country_id']);
            $table->dropForeign(['activity_category_id']);
        });
        Schema::dropIfExists('pack_destination_infos');
    }
};
