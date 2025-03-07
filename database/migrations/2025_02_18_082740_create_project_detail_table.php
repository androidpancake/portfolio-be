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
        Schema::create('project_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('project_id');
            $table->string('background');
            $table->string('stack1')->nullable();
            $table->string('stack2')->nullable();
            $table->string('stack3')->nullable();
            $table->string('db');
            $table->string('logo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_detail');
    }
};
