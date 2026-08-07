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
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('reviewer_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->enum('review_type', ['Monthly', 'Quarterly', 'Annual']);
            $table->string('period');
            $table->decimal('rating', 3, 2);
            $table->text('strengths')->nullable();
            $table->text('improvements')->nullable();
            $table->date('review_date');
            $table->enum('status', ['Pending', 'Completed'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
