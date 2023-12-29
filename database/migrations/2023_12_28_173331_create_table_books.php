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
        Schema::create('table_books', function (Blueprint $table) {
            $table->id('book_id');
            $table->integer('ISBN');
            $table->string('book_title');
            $table->string('book_author');
            $table->integer('number_of_copies');
            $table->unsignedBigInteger('library_id');

            $table->foreign('library_id')->references('library_id')->on('table_libraries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_books');
    }
};
