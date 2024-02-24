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
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->constrained()->references('id')->on('projects')->cascadeOnDelete();
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->constrained()->references('id')->on('projects')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
};
