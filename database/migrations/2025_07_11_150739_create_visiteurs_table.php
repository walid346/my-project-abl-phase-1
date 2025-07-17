<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visiteurs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->string('session_id');
            $table->timestamp('visit_date');
            $table->string('user_agent')->nullable();
            $table->foreignId('article_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['ip_address', 'visit_date']);
            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visiteurs');
    }
};