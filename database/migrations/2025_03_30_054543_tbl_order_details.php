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
        Schema::create('tbl_order_detail', function (Blueprint $table) {
            $table->id('order_detail_id'); // Khóa chính

            // Không dùng khóa ngoại, chỉ lưu ID
            $table->unsignedBigInteger('order_id');  // ID đơn hàng
            $table->unsignedBigInteger('product_id'); // ID sản phẩm

            $table->integer('product_quantity');  // Số lượng sản phẩm
            $table->decimal('product_price', 10, 2); // Giá mỗi sản phẩm

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_order_details');
    }
};
