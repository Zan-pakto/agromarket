<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type'); // fixed, percent
            $table->decimal('value', 10, 2);
            $table->decimal('min_spend', 10, 2)->default(0.00);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('coupons')->insert([
            [
                'code' => 'WELCOME50',
                'type' => 'fixed',
                'value' => 50.00,
                'min_spend' => 200.00,
                'expires_at' => now()->addMonths(1),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'SUMMER20',
                'type' => 'percent',
                'value' => 20.00,
                'min_spend' => 0.00,
                'expires_at' => now()->addDays(15),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
