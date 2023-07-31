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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('status');
            $table->string('file_name')->nullable(); // Add a new column to store the file name
            $table->string('file_path')->nullable(); // Add a new column to store the file path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.php 
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
