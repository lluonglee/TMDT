<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_payment', function (Blueprint $table) {
            // Thêm cột VNPay
            $table->string('vnpay_transaction_id')->nullable()->after('payment_status');
            $table->string('vnpay_status')->nullable()->after('vnpay_transaction_id');
            // Tối ưu payment_method thành enum
            $table->enum('payment_method', ['bằng thẻ', 'tiền mặt', 'VNPay'])
                ->default('tiền mặt')
                ->change();
            // Tối ưu payment_status
            $table->string('payment_status', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('tbl_payment', function (Blueprint $table) {
            $table->dropColumn(['vnpay_transaction_id', 'vnpay_status']);
            $table->string('payment_method')->change();
            $table->string('payment_status', 255)->change();
        });
    }
};
