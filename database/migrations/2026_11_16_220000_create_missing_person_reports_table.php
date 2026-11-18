<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('missing_person_reports', function(Blueprint $table) { $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->unsignedTinyInteger('age')->nullable(); $table->string('last_known_location'); $table->date('last_seen_date'); $table->string('photo_path')->nullable(); $table->string('contact_method'); $table->text('details')->nullable(); $table->string('status')->default('Missing'); $table->timestamps(); }); }
 public function down(): void { Schema::dropIfExists('missing_person_reports'); }
};
