<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disaster_alerts', function (Blueprint $table) {
            $table->string('affected_area', 180)->nullable()->after('location');
            $table->longText('safety_instructions')->nullable()->after('body');
            $table->string('status', 20)->default('active')->index()->after('safety_instructions');
        });

        DB::table('disaster_alerts')->where('severity', 'warning')->update(['severity' => 'high']);
        DB::table('disaster_alerts')->where('severity', 'information')->update(['severity' => 'moderate']);
    }

    public function down(): void
    {
        Schema::table('disaster_alerts', function (Blueprint $table) {
            $table->dropColumn(['affected_area', 'safety_instructions', 'status']);
        });
    }
};
