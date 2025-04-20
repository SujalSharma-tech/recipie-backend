<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('cascade');


            // Keep title for compatibility
            $table->string('title')->nullable();

            $table->text('description')->nullable();

            // Change time to string to support formats like "45 minutes"
            $table->string('cooking_time')->nullable();

            // Keep integer time field if needed
            $table->integer('time')->nullable();

            $table->enum('difficulty', ['Easy', 'Medium', 'Hard'])->default('Medium');

            // Change servings to string to support formats like "4-5 people"
            $table->string('servings')->nullable();

            $table->string('cuisine_type')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('allow_copy')->default(false);
            $table->string('image')->nullable();
            $table->string('youtube_video')->nullable();
            $table->jsonb('ingredients');

            // Add instructions field to match seeder
            $table->jsonb('instructions')->nullable();

            // Keep steps field for compatibility
            $table->jsonb('steps')->nullable();

            $table->jsonb('nutrition')->nullable();
            $table->jsonb('tags')->nullable();
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
