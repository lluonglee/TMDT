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
        Schema::table('tbl_customer', function (Blueprint $table) {
            $table->integer('login_attempts')->default(0); // số lần đăng nhập sai
            $table->timestamp('last_failed_login')->nullable(); // thời điểm đăng nhập sai gần nhất
        });
    }

    public function down(): void
    {
        Schema::table('tbl_customer', function (Blueprint $table) {
            $table->dropColumn(['login_attempts', 'last_failed_login']);
        });
    }
};
