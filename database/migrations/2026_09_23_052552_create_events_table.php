<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('type');
            $table->string('title');
            $table->date('event_date')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('gallery')->nullable();
            $table->text('description')->nullable();
            $table->text('mp_access_token')->nullable();
            $table->text('mp_public_key')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
