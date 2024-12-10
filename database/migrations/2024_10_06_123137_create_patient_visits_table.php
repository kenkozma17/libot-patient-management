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
        Schema::create('patient_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained();
            $table->foreignId('invoice_id')->constrained();
            $table->string('diagnosis')->nullable();
            $table->string('requesting_physician')->nullable();
            $table->datetime('visit_date')->nullable();
            $table->integer('patient_age')->nullable();
            $table->string('patient_type')->nullable();
            $table->string('patient_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_visits');
    }
};
