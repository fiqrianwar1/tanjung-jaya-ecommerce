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
            $table->timestamp('shipped_at')->nullable()->after('status')->index();
            $table->index('created_at');
            $table->index('status');
            $table->index(['status', 'created_at'], 'orders_status_created_at_idx');
            $table->index(['user_id', 'total'], 'orders_user_id_total_idx');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('stock');
        });

        Schema::table('stock_logs', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('product_id');
            $table->index(['product_id', 'created_at'], 'stock_logs_product_created_idx');
        });

        Schema::table('return_requests', function (Blueprint $table) {
            $table->index('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status']);
            $table->dropIndex('orders_status_created_at_idx');
            $table->dropIndex('orders_user_id_total_idx');
            $table->dropIndex(['shipped_at']);
            $table->dropColumn('shipped_at');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['stock']);
        });

        Schema::table('stock_logs', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['product_id']);
            $table->dropIndex('stock_logs_product_created_idx');
        });

        Schema::table('return_requests', function (Blueprint $table) {
            $table->dropIndex(['reason']);
        });
    }
};
