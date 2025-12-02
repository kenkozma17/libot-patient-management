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
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('discount_percentage', 10, 2)
                ->default(0)
                ->after('amount_payable');
            $table->decimal('amount_discounted', 10, 2)
                ->default(0)
                ->after('discount_percentage');
            $table->string('or_number')
                ->after('amount_discounted')
                ->nullable();
            $table->boolean('is_paid')
                ->after('or_number')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('discount_percentage');
            $table->dropColumn('amount_discounted');
            $table->dropColumn('or_number');
            $table->dropColumn('is_paid');
        });
    }
};
