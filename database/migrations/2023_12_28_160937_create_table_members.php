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
        Schema::create('table_members', function (Blueprint $table) {
            $table->id('member_id', 255);
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('address', 100);
            $table->string('contact_no', 11);
            $table->unsignedBigInteger('library_id');
            $table->string('email_address', 50);
            $table->string('password', 255);

            $table->foreign('library_id')->references('library_id')->on('table_libraries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_members');
    }
};
