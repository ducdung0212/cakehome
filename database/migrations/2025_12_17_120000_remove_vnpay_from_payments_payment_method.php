<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payments')) {
            return;
        }

        // Ensure no remaining rows use the removed method.
        DB::table('payments')
            ->where('payment_method', 'vnpay')
            ->update(['payment_method' => 'momo']);

        $driver = DB::connection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('cash','momo') NOT NULL");
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('payments')) {
            return;
        }

        $driver = DB::connection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('cash','momo','vnpay') NOT NULL");
        }
    }
};
