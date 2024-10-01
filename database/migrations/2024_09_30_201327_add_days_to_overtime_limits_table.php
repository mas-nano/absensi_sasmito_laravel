<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('overtime_limits', function (Blueprint $table) {
            $table->jsonb('days')->default(json_encode([0, 1, 2, 3, 4, 5, 6]));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('overtime_limits', function (Blueprint $table) {
            $table->dropColumn('days');
        });
    }
};
