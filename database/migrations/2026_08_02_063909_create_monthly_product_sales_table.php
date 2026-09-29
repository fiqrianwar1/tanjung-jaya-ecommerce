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
        Schema::create('monthly_product_sales', function (Blueprint $table) {
            $table->id();
            $table->string('month')->index();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('total_qty_sold')->default(0);
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_product_sales');
    }
};
