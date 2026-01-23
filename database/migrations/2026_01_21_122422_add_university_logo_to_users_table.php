<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambah kolom logo setelah jenis PT, nullable (opsional) atau tidak terserah kebutuhan
            $table->string('university_logo')->nullable()->after('university_type');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('university_logo');
        });
    }
};
