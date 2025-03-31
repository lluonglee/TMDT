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
        Schema::create('tbl_brand', function (Blueprint $table) {
            $table->id('brand_id'); // ✅ BIGINT UNSIGNED AUTO_INCREMENT
            $table->string('brand_name');
            $table->text('brand_desc')->nullable();
            $table->integer('brand_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_brand');
    }
};
