<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rescue_request_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rescue_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 40);
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['rescue_request_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rescue_request_activities');
    }
};
