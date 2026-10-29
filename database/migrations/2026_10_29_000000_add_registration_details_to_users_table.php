<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('role', 20)->default('citizen')->after('phone');
            $table->string('volunteer_id', 100)->nullable()->unique()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['volunteer_id']);
            $table->dropColumn(['phone', 'role', 'volunteer_id']);
        });
    }
};
