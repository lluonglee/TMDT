<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_order', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('order_id');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_order', function (Blueprint $table) {
            $table->dropColumn('session_id');
        });
    }
};
