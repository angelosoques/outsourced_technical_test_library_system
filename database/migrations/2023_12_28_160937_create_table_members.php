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
            $table->id('member_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->string('contact_no');
            $table->string('library_id');
            $table->string('email_address');
            $table->string('password');
            
            $table->foreign_key('library_id')->references('library_id')->on('table_libraries');
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
