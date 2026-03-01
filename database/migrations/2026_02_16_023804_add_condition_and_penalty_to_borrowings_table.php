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
        Schema::table('borrowings', function (Blueprint $table) {
            $table->enum('return_condition', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->nullable()->after('actual_return_date');
            $table->text('damage_notes')->nullable()->after('return_condition');
            $table->decimal('repair_cost', 10, 2)->default(0)->after('damage_notes');
            $table->integer('late_days')->default(0)->after('repair_cost');
            $table->decimal('late_fee', 10, 2)->default(0)->after('late_days');
            $table->decimal('total_penalty', 10, 2)->default(0)->after('late_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn([
                'return_condition',
                'damage_notes',
                'repair_cost',
                'late_days',
                'late_fee',
                'total_penalty'
            ]);
        });
    }
};
