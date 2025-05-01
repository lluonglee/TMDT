<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_shipping', function (Blueprint $table) {
            $table->integer('shipping_fee')->default(0)->after('maqh');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_shipping', function (Blueprint $table) {
            $table->dropColumn('shipping_fee');
        });
    }
};
