<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rescue_requests', function (Blueprint $table) {
            $table->id();

            // المستخدم الذي يعرض المساعدة
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // منشور الإنقاذ
            $table->foreignId('post_id')
                ->constrained('posts')
                ->cascadeOnDelete();

            // حالة الطلب
            $table->enum('status', [
                'PENDING',
                'ACCEPTED',
                'REJECTED',
                'CANCELLED'
            ])->default('PENDING');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // منع إرسال أكثر من طلب مساعدة لنفس المنشور
            $table->unique(['user_id', 'post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rescue_requests');
    }
};
