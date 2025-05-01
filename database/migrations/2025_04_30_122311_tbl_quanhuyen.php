<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tbl_quanhuyen', function (Blueprint $table) {
            $table->string('maqh', 5)->primary();
            $table->string('name', 100);
            $table->string('type', 30);
            $table->string('matp', 5);
            $table->foreign('matp')->references('matp')->on('tbl_tinhthanhpho')->onDelete('cascade');
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_quanhuyen');
    }
};