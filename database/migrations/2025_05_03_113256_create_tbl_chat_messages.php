<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable(); // Thay customer_id bằng session_id
            $table->text('message');
            $table->boolean('is_bot')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_chat_messages');
    }
};
