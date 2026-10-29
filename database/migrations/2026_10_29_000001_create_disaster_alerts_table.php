<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disaster_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 180);
            $table->string('kind', 20)->default('alert')->index();
            $table->string('category', 40)->default('general');
            $table->string('severity', 20)->default('information')->index();
            $table->string('location', 160);
            $table->string('summary', 280);
            $table->longText('body');
            $table->timestamp('published_at')->useCurrent()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disaster_alerts');
    }
};
