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
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('icon', 255)->nullable();

            // Foreign key: package
            $table->unsignedBigInteger('package_id')->index();
            $table->foreign('package_id')->references('id')->on('packages')->cascadeOnDelete();

            // Reference fields
            $table->uuid('package_uuid')->nullable();
            $table->string('package_title', 255)->nullable();
            $table->json('cities')->nullable();
            $table->json('activities')->nullable();
            $table->text('meals')->nullable();

            // Newly added
            $table->string('date', 32)->nullable();
            $table->string('day_number', 32)->nullable();


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
        });
        Schema::dropIfExists('pack_itenaries');
    }
};
