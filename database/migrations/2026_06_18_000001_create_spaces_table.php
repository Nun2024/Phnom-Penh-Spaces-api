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
        Schema::create('spaces', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('location');
            $table->string('space_type'); // 'podcast', 'meeting', 'gallery', 'workshop'
            $table->decimal('price_per_hour', 8, 2);
            $table->integer('capacity');
            $table->text('description');
            $table->string('status')->default('Active'); // 'Active', 'Maintenance'
            $table->json('images')->nullable(); // JSON list of image URLs
            
            // Amenities
            $table->boolean('wifi')->default(false);
            $table->boolean('whiteboard')->default(false);
            $table->boolean('ac')->default(true);
            $table->boolean('soundproofing')->default(true);
            $table->boolean('natural_light')->default(false);
            $table->boolean('refreshments')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spaces');
    }
};
