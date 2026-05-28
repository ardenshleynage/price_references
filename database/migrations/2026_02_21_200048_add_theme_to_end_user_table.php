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
        Schema::table('end_user', function (Blueprint $table) {
            $table->string('theme')->default('light')->after('last_time_connect');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('end_user', function (Blueprint $table) {
            $table->dropColumn('theme');
        });
    }
};
