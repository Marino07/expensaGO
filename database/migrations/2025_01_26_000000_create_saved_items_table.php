<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type');

            $table->string('api_place_id')->nullable();
            $table->string('place_name')->nullable();
            $table->string('place_address')->nullable();
            $table->json('place_details')->nullable();

            $table->foreignId('event_id')->nullable()->constrained('local_events')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_items');
    }
};
