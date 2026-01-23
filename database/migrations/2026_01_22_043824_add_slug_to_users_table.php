<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('university_name');
        });

        // Auto-generate slug untuk data yang sudah ada
        $users = DB::table('users')->whereNotNull('university_name')->get();
        
        foreach ($users as $user) {
            $slug = Str::slug($user->university_name);
            
            // Cek duplikat, tambahkan angka jika perlu
            $originalSlug = $slug;
            $count = 1;
            
            while (DB::table('users')->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            
            DB::table('users')
                ->where('id', $user->id)
                ->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};