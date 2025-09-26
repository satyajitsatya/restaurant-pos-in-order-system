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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Foreign key to categories
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2); // Price with 2 decimal places (99999.99)
            $table->string('image')->nullable();
            $table->boolean('is_veg')->default(true); // Vegetarian or non-veg
            $table->enum('spice_level', ['mild', 'medium', 'hot'])->default('mild');
            $table->integer('prep_time')->default(15); // Preparation time in minutes
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
