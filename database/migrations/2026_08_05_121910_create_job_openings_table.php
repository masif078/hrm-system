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
        Schema::create('job_openings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->integer('vacancies');
            $table->enum('employment_type', ['Full Time', 'Part Time', 'Internship', 'Contract'])->default('Full Time');
            $table->string('location');
            $table->string('salary_range')->nullable();
            $table->text('description')->nullable();
            $table->date('closing_date')->nullable();
            $table->enum('status', ['Open', 'Closed'])->default('Open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_openings');
    }
};
