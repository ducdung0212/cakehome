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
        Schema::table('orders', function (Blueprint $table) {
            // Thêm cột delivery_at để lưu thời gian giao hàng cụ thể
            $table->dateTime('delivery_at')->nullable()->after('shipping_address_id');

            // Xóa cột cũ delivery_date và delivery_time (nếu không cần thiết)
            // Nếu đã có data trong production, hãy migrate data trước khi drop
            $table->dropColumn(['delivery_date', 'delivery_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rollback: Xóa delivery_at và khôi phục delivery_date, delivery_time
            $table->dropColumn('delivery_at');
            $table->dateTime('delivery_date')->nullable();
            $table->dateTime('delivery_time')->nullable();
        });
    }
};
