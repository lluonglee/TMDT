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
        Schema::create('tbl_order', function (Blueprint $table) {
            $table->id('order_id'); // Khóa chính

            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('shipping_id');
            $table->unsignedBigInteger('payment_id');

            $table->decimal('order_total', 15, 2);
            $table->enum('order_status', ['Đang xử lý', 'Hoàn thành', 'Đã hủy'])->default('Đang xử lý');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_order');
    }
};
