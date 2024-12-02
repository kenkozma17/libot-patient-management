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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items');
            $table->date('date_received')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('lot_number')->nullable();
            $table->integer('quantity')->nullable();
            $table->date('date_opened')->nullable();
            $table->integer('stock')->nullable();
            $table->string('transaction_type')->nullable(); // INCREASE OR DECREASE
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
