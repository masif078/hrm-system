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
        Schema::create('appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('performance_review_id')->nullable()->constrained('performance_reviews')->onDelete('set null');
            $table->string('rating_class');
            $table->enum('action_type', ['Increment', 'Promotion', 'Both', 'None'])->default('None');
            $table->decimal('previous_salary', 10, 2);
            $table->decimal('new_salary', 10, 2);
            $table->foreignId('previous_designation_id')->nullable()->constrained('designations')->onDelete('set null');
            $table->foreignId('new_designation_id')->nullable()->constrained('designations')->onDelete('set null');
            $table->date('effective_date');
            $table->enum('status', ['Draft', 'Approved', 'Rejected'])->default('Draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appraisals');
    }
};
