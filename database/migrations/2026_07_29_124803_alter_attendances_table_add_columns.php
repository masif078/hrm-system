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
        if (!Schema::hasColumn('attendances', 'employee_id')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->date('date');
                $table->time('check_in')->nullable();
                $table->time('check_out')->nullable();
                $table->enum('status', ['Present', 'Absent', 'Late', 'Leave']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn(['employee_id', 'date', 'check_in', 'check_out', 'status']);
        });
    }
};
