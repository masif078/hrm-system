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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('resume')->nullable();
            $table->text('skills')->nullable();
            $table->integer('experience')->default(0);
            $table->string('qualification')->nullable();
            $table->string('source')->nullable();
            $table->enum('status', ['Applied', 'Shortlisted', 'HR Interview', 'Technical Interview', 'Final Interview', 'Offer Sent', 'Accepted', 'Rejected', 'Hired'])->default('Applied');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
