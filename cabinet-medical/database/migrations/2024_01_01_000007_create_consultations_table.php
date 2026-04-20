<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->text('chief_complaint'); // motif principal
            $table->text('diagnosis');
            $table->text('notes')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->integer('blood_pressure_systolic')->nullable();
            $table->integer('blood_pressure_diastolic')->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->integer('heart_rate')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'insurance'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
