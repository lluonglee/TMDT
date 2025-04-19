<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_promotion', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->decimal('discount_value', 10, 2); // Giá trị giảm (VNĐ hoặc %)
            $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed'); // Loại giảm: cố định hoặc phần trăm
            $table->decimal('max_discount', 10, 2)->nullable(); // Giới hạn tối đa khi giảm theo %
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('usage_limit')->default(0); // Số lần sử dụng tối đa (0 = không giới hạn)
            $table->integer('used_count')->default(0); // Số lần đã sử dụng
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_promotion');
    }
};
