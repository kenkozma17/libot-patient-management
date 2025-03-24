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
        Schema::create('patient_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained();
            $table->decimal('amount', 10, 2);
            $table->decimal('interest_rate')->default(1);
            $table->decimal('service_fee')->default(200);
            $table->string('purpose')->nullable();
            $table->integer('duration_months')->default(1);
            $table->decimal('capital_build_up')->default(1);
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('check_no')->nullable();
            $table->datetime('date_released');
            $table->decimal('net_amount_released', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
