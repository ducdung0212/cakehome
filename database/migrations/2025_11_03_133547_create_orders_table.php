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
        Schema::create ('orders', function (Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('subtotal_price',10,2);
            $table->decimal('total_price',10,2);
            $table->string('voucher_id')->nullable();
            $table->decimal('discount_amount',10,2)->default(0);
            $table->string('status')->default('pending'); //pending, completed, cancelled
            $table->foreignId('shipping_address_id')->constrained('shipping_addresses')->onDelete('cascade');
            $table->enum('delivery_method',['delivery','pickup'])->default('delivery');
            $table->dateTime('delivery_date')->nullable();
            $table->dateTime('delivery_time')->nullable();
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
