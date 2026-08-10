<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adoption_requests', function (Blueprint $table) {
            $table->id();

            // المستخدم الذي يطلب التبني
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // منشور القطة
            $table->foreignId('post_id')
                ->constrained('posts')
                ->cascadeOnDelete();

            // رسالة اختيارية من طالب التبني
            $table->text('message')->nullable();

            // حالة الطلب
            $table->enum('status', [
                'PENDING',
                'APPROVED',
                'REJECTED',
                'CANCELLED'
            ])->default('PENDING');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // منع المستخدم من إرسال أكثر من طلب لنفس المنشور
            $table->unique(['user_id', 'post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adoption_requests');
    }
};
