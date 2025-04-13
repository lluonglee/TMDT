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
        Schema::create('tbl_employee', function (Blueprint $table) {
            $table->id('employee_id'); // Khóa chính tự động tăng
            $table->string('employee_name');
            $table->string('employee_email')->unique(); // Đảm bảo email là duy nhất
            $table->string('employee_password');
            $table->string('employee_phone');
            $table->tinyInteger('role')->default(0); // 0: nhân viên, 1: quản trị viên
            $table->tinyInteger('status')->default(1); // 1: hoạt động, 0: bị khóa
            $table->json('permissions')->nullable();
            $table->timestamps(); // Tự động thêm created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_employee');
    }
};
