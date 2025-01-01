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
        Schema::create('patient_visit_lab_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_visit_id')->constrained('patient_visits')->onDelete('cascade');
            $table->string('result_path');
            $table->string('result_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_visit_lab_test_results');
    }
};
