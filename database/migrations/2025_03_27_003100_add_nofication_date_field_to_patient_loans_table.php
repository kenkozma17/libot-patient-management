<?php

use Carbon\Carbon;
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
        Schema::table('patient_loans', function (Blueprint $table) {
            $table->datetime('notification_date')
                ->default(Carbon::now())
                ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_loans', function (Blueprint $table) {
            $table->dropColumn('notification_date');
        });
    }
};
