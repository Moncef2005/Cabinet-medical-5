<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('specialty');
            $table->string('license_number')->unique();
            $table->text('description')->nullable();
            $table->decimal('consultation_price', 8, 2)->default(200.00);
            $table->integer('consultation_duration')->default(30); // minutes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
