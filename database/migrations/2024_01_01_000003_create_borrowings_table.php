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
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->date('borrow_date');
            $table->date('requested_return_date');
            $table->date('actual_return_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('equipment_id');
            $table->index('status');
            $table->index('borrow_date');
            $table->index('actual_return_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
