<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('full_name', 100);
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();

            $table->string('password');

            $table->string('phone', 20)->nullable();
            $table->text('avatar_url')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
