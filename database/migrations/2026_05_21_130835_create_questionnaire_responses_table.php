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
    Schema::create('questionnaire_responses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->integer('sdq_score')->nullable();
        $table->integer('psc17_score')->nullable();
        $table->integer('sassv_score')->nullable();
        $table->enum('status', ['draft', 'completed'])->default('draft');
        $table->timestamp('completed_at')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_responses');
    }
};
