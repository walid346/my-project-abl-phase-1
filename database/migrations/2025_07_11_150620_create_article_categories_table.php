<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('article_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamp('assigned_at')->useCurrent();
            $table->primary(['article_id', 'category_id']); 
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('article_categories');
    }
};