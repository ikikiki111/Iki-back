<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim')->unique()->nullable()->after('name');
            $table->string('foto')->nullable()->after('nim');
            $table->string('program_studi')->nullable()->after('foto');
            $table->string('angkatan')->nullable()->after('program_studi');
            $table->string('no_hp')->nullable()->after('angkatan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim', 'foto', 'program_studi', 'angkatan', 'no_hp']);
        });
    }
};
