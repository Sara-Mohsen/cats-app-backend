<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->enum('post_type', [
        'NORMAL',
        'RESCUE',
        'ADOPTION'
    ]);

    $table->string('name', 50)->nullable();
    $table->unsignedInteger('age_years')->nullable();

    $table->enum('gender', [
        'MALE',
        'FEMALE'
    ])->nullable();

    $table->foreignId('breed_id')
        ->nullable()
        ->constrained('breeds');

    $table->foreignId('city_id')
        ->constrained('cities');

    $table->text('image_url');

    $table->text('personality_description')->nullable();

    $table->boolean('is_neutered')->nullable();
    $table->boolean('is_vaccinated')->nullable();

    $table->boolean('is_injured')->nullable();

    $table->text('injury_description')->nullable();

    $table->string('contact_number', 20)->nullable();

    $table->enum('status', [
        'ACTIVE',
        'CLOSED'
    ])->default('ACTIVE');

    $table->timestamp('created_at')->useCurrent();
     });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
