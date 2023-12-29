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
        Schema::create('table_borrowed_books', function (Blueprint $table) {
            $table->id('borrowed_books_id', 255);
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('member_id');
            $table->tinyInteger('returned')->nullable(false)->default(0);

            $table->foreign('book_id')->references('book_id')->on('table_books');
            $table->foreign('member_id')->references('member_id')->on('table_members');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_borrowed_books');
    }
};
