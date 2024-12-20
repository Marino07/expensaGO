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
        Schema::create('planner_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planner_id')->constrained()->cascadeOnDelete();
            $table->integer('day_number');
            $table->json('main_attraction');
            $table->json('places_to_visit');
            $table->json('estimated_costs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planner_days');
    }
};
