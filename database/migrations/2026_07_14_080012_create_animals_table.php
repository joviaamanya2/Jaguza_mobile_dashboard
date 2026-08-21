<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('identification_number')->nullable()->unique();
            $table->string('name')->nullable();
            $table->enum('type', ['cattle', 'poultry', 'goats', 'pigs', 'sheep', 'rabbits', 'fish', 'other']);
            $table->string('breed')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->integer('age')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->enum('health_status', ['healthy', 'sick', 'injured', 'recovering', 'pregnant', 'lactating', 'critical'])->default('healthy');
            $table->foreignId('farm_id')->constrained()->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('photo')->nullable();
            $table->date('date_bought')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};