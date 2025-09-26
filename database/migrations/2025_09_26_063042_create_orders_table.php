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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->string('customer_name', 100);
            $table->integer('guest_count')->default(1);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'accepted', 'preparing', 'ready', 'served', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['counter', 'card', 'upi', 'cash'])->default('counter');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
