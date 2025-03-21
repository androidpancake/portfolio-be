<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->string('title');
            $table->string('slug');
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('url');
            $table->unsignedBigInteger('category_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status');
            $table->timestamp('scehduled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
