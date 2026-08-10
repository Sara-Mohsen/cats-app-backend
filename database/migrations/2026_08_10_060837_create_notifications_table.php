<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // المستخدم الذي سيستلم الإشعار
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // المستخدم الذي تسبب في الإشعار
            $table->foreignId('sender_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // المنشور المرتبط بالإشعار
            $table->foreignId('post_id')
                ->nullable()
                ->constrained('posts')
                ->cascadeOnDelete();

            // نوع الإشعار
            $table->enum('type', [
                'LIKE',
                'COMMENT',
                'ADOPTION_REQUEST',
                'RESCUE_REQUEST',
                'SYSTEM'
            ]);

            // نص الإشعار
            $table->string('message', 255);

            // هل تمت قراءة الإشعار؟
            $table->boolean('is_read')->default(false);

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
