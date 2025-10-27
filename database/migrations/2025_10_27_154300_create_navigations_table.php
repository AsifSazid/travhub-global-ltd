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
        Schema::create('navigations', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', '36')->unique();
            $table->string('title');
            $table->foreignId('parent_id')->nullable()->constrained('navigations')->onDelete('cascade');
            $table->string('nav_icon')->nullable();
            $table->unsignedBigInteger('created_by'); // Better to use user ID (foreign key)
            $table->string('created_by_uuid')->nullable();
            $table->string('url')->nullable();
            $table->string('route')->nullable();
            $table->boolean('is_active')->default(true); // Quick toggle for visibility
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigations');
    }
};
