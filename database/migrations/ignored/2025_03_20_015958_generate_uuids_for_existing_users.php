<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            DB::table('users')->where('id', $user->id)->update(['id' => (string) Str::uuid()]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            DB::table('users')->update(['id' => $user->id]);
        }
    }
};
