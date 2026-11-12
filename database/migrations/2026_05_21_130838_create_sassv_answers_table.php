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
    Schema::create('sassv_answers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('response_id')->constrained('questionnaire_responses')->cascadeOnDelete();
        $table->integer('question_number');
        $table->integer('answer_value'); // 1-6 skala Likert
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sassv_answers');
    }
};
