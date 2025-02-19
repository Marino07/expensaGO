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
        Schema::create('default_images', function (Blueprint $table) {
            $table->id();
            $table->string('event_image')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('event_title')->nullable();
            $table->string('event_url')->nullable();
            $table->text('place_image')->nullable();
            $table->string('place_name')->nullable();
            $table->string('place_location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_images');
    }
};
