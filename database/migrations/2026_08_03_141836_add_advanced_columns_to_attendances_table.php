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
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('working_hours', 5, 2)->nullable();
            $table->decimal('overtime_hours', 5, 2)->default(0.00);
            $table->boolean('late_arrival')->default(false);
            $table->boolean('early_checkout')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['working_hours', 'overtime_hours', 'late_arrival', 'early_checkout']);
        });
    }
};
