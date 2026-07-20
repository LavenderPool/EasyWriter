<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('share_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manga_id')->constrained()->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->string('label')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamps();

            $table->index(['manga_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('share_links');
    }
};
