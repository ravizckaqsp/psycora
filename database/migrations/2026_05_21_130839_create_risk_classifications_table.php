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
    Schema::create('risk_classifications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('response_id')->constrained('questionnaire_responses')->cascadeOnDelete();
        $table->string('sdq_category');    // Normal, Borderline, Abnormal
        $table->string('psc17_category');  // Negatif, Positif
        $table->string('sassv_category');  // Tidak Kecanduan, Kecanduan
        $table->string('overall_risk');    // Rendah, Sedang, Tinggi, Sangat Tinggi
        $table->text('recommendation')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_classifications');
    }
};
