<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable(); // Ringkasan singkat
            $table->longText('content');
            $table->string('featured_image')->nullable();
            
            // FITUR LINK OPSIONAL
            $table->string('link_text')->nullable(); // Text yang diklik
            $table->string('link_url')->nullable();  // URL tujuan
            
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            
            // Author (admin yang buat)
            $table->foreignId('author_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
