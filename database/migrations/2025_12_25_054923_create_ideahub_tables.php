<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. UPDATE Tabel Users (Menyimpan Q1 - Q4)
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu agar tidak error jika kolom sudah ada (untuk safety)
            if (!Schema::hasColumn('users', 'university_name')) {
                $table->string('university_name')->nullable(); 
                $table->string('university_type')->nullable(); 
                $table->boolean('has_disability_study_program')->default(false); 
                $table->string('university_category')->nullable(); 
                $table->string('phone_number')->nullable();
            }
        });

        // 2. Tabel Questions (Mulai dari Q5 ke atas)
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('dimension')->nullable()->index(); 
            $table->text('question');
            $table->string('code')->nullable(); 
            $table->string('type'); 
            
            // Logic Dependency
            $table->foreignId('depends_on_question_id')
                  ->nullable()
                  ->constrained('questions')
                  ->onDelete('set null');
            $table->string('depends_on_value')->nullable();
            
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('order_position')->default(0);
            
            $table->timestamps();
        });

        // 3. Tabel Options (SEBELUMNYA question_options, KITA UBAH JADI options)
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->string('option_label');
            $table->string('value');
            
            // FITUR BARU: Opsi "Lainnya" (Input Teks)
            $table->boolean('has_text_input')->default(false);
            
            $table->timestamps();
        });

        // 4. Tabel Submissions
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // FITUR BARU: Status Lengkap
            $table->enum('status', ['draft', 'submitted', 'rejected', 'accepted'])->default('draft');
            
            // FITUR BARU: Feedback Admin
            $table->text('admin_feedback')->nullable();
            
            $table->integer('current_step')->default(1); 
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        // 5. Tabel Submission Values
        Schema::create('submission_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->text('value')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_values');
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('options'); // Pastikan drop 'options'
        Schema::dropIfExists('questions');
        
        if (Schema::hasColumn('users', 'university_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn([
                    'university_name', 
                    'university_type', 
                    'has_disability_study_program', 
                    'university_category', 
                    'phone_number'
                ]);
            });
        }
    }
};