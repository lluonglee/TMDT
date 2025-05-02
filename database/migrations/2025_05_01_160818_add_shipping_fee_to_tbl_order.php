<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingFeeToTblOrder extends Migration
{
    public function up()
    {
        Schema::table('tbl_order', function (Blueprint $table) {
            $table->decimal('shipping_fee', 10, 0)->default(0)->after('order_total');
        });
    }

    public function down()
    {
        Schema::table('tbl_order', function (Blueprint $table) {
            $table->dropColumn('shipping_fee');
        });
    }
}
