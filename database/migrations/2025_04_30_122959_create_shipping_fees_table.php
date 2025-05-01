<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingFeesTable extends Migration
{
    public function up()
    {
        Schema::create('shipping_fees', function (Blueprint $table) {
            $table->id();
            $table->string('matp', 5);
            $table->string('maqh', 5)->nullable();
            $table->integer('fee');
            $table->timestamps();
            $table->foreign('matp')->references('matp')->on('tbl_tinhthanhpho')->onDelete('cascade');
            $table->foreign('maqh')->references('maqh')->on('tbl_quanhuyen')->onDelete('cascade');
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping_fees');
    }
}