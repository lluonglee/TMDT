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
        Schema::create('tbl_product', function (Blueprint $table) {
            $table->id('product_id'); // ✅ BIGINT UNSIGNED AUTO_INCREMENT
            $table->string('product_name');
            $table->unsignedBigInteger('category_id'); // ✅ UNSIGNED BIGINT
            $table->unsignedBigInteger('brand_id');    // ✅ UNSIGNED BIGINT
            $table->decimal('product_price', 10, 2);
            $table->integer('product_quantity');
            $table->text('product_desc')->nullable();
            $table->string('product_image')->nullable();
            $table->tinyInteger('product_status')->default(1);
            $table->string('product_size')->nullable();
            $table->string('product_color')->nullable();
            $table->string('product_material')->nullable();
            $table->decimal('discount', 5, 2)->default(0);
            $table->timestamps();

            // ✅ Thiết lập khóa ngoại đúng kiểu dữ liệu
            $table->foreign('category_id')->references('category_id')->on('tbl_category_product')->onDelete('cascade');
            $table->foreign('brand_id')->references('brand_id')->on('tbl_brand')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_product');
    }
};
