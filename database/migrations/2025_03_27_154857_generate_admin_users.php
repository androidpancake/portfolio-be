<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::create([
            'user_id' => 'admin-portfolio-1',
            'username' => 'adminpfe1',
            'is_approved' => '1',
            'password' => 'Admin123',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::where('user_id', 'admin-portfolio-1')->delete();
    }
};
