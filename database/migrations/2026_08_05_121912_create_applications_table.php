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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            $table->foreignId('job_opening_id')->constrained('job_openings')->onDelete('cascade');
            $table->enum('status', ['Applied', 'Shortlisted', 'HR Interview', 'Technical Interview', 'Final Interview', 'Offer Sent', 'Accepted', 'Rejected', 'Hired'])->default('Applied');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
