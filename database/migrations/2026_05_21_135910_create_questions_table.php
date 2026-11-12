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
    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->enum('type', ['sdq', 'psc17', 'sassv']);
        $table->integer('number');
        $table->text('text');
        $table->string('subscale')->nullable(); // subskala SDQ/PSC17
        $table->boolean('reverse_scored')->default(false); // untuk SDQ item tertentu
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
