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
        Schema::create('books_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->ondelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->ondelete('cascade');
            // $table->timestamp('created_at');
            // $table->timestamp('updated_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_categories');
    }
};
