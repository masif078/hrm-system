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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('asset_category_id')->constrained('asset_categories')->onDelete('cascade');
            $table->string('serial_number')->unique();
            $table->decimal('cost', 10, 2)->default(0.00);
            $table->date('purchase_date');
            $table->date('warranty_expiry')->nullable();
            $table->enum('status', ['Available', 'Assigned', 'Maintenance', 'Lost'])->default('Available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
