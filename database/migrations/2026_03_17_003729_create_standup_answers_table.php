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
        Schema::create('standup_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standup_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('standup_questions')->cascadeOnDelete();
            $table->text('answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standup_answers');
    }
};
