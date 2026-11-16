<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('volunteer_applications', function(Blueprint $table) { $table->id(); $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete(); $table->string('phone',30); $table->text('skills'); $table->string('availability'); $table->text('experience')->nullable(); $table->string('status')->default('Pending'); $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete(); $table->timestamps(); }); }
 public function down(): void { Schema::dropIfExists('volunteer_applications'); }
};
