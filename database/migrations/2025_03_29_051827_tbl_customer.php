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
        Schema::create('tbl_customer', function (Blueprint $table) {
            $table->id('customer_id'); // ✅ BIGINT UNSIGNED AUTO_INCREMENT
            $table->string('customer_name');
            $table->string('customer_email')->unique(); // ✅ Thêm ràng buộc unique
            $table->string('customer_password');
            $table->string('customer_phone');
            $table->tinyInteger('status')->default(1); // 1: hoạt động, 0: bị khóa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_customer');
    }
};
