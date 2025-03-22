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
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            Schema::dropColumns('personal_access_tokens', [
                'tokenable_type',
                'tokenable_id'
            ]);
            $table->uuidMorphs('tokenable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            Schema::dropColumns('personal_access_tokens', [
                'tokenable_type',
                'tokenable_id'
            ]);
            $table->morphs('tokenable');
        });
    }
};
