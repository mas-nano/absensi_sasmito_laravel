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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->date('start_date');
            $table->date('to_date');
            $table->integer('status');
            $table->text('reason');
            $table->string('type');
            $table->string('title');
            $table->string('photo')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained()->references('id')->on('users')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
