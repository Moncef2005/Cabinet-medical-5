<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->dateTime('scheduled_at');
            $table->integer('duration')->default(30); // minutes
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'no_show'])->default('pending');
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
