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
        Schema::create('local_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('location');
            $table->date('start_date');
            $table->text('description');
            $table->string('category')->nullable();
            $table->string('type');
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('price_min', 8, 2)->nullable();
            $table->decimal('price_max', 8, 2)->nullable();
            $table->string('google_place_id')->nullable(); // Optional reference to Google Place
            $table->string('image_url')->nullable(); // URL for event image
            $table->boolean('free')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_events');
    }
};
