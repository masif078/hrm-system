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
        if (!Schema::hasColumn('tasks', 'project_id')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->foreignId('project_id')->constrained()->cascadeOnDelete();
                $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->date('due_date');
                $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
                $table->enum('priority', ['Low', 'Medium', 'High'])->default('Low');
            });
        }
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['employee_id']);
            $table->dropColumn(['project_id', 'employee_id', 'title', 'description', 'due_date', 'status', 'priority']);
        });
    }
};
