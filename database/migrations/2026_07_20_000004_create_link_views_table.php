<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('link_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('share_link_id')->constrained()->cascadeOnDelete();
            $table->string('country_code', 2)->nullable();
            $table->string('country_name')->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamp('viewed_at');
            $table->timestamps();

            $table->index(['share_link_id', 'country_code']);
            $table->index(['share_link_id', 'viewed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_views');
    }
};
