<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('extension_workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('expertise_area'); // Crop Farming, Livestock, Mixed Farming, Aquaculture, etc.
            $table->string('education_level'); // Diploma, Degree, Masters, PhD
            $table->string('assigned_region'); // Geographical region assigned
            $table->string('phone_number');
            $table->integer('years_of_experience')->default(0);
            $table->string('languages_spoken')->nullable(); // e.g. English, Luganda, Swahili
            $table->boolean('is_available')->default(true);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_farm_visits')->default(0);
            $table->text('bio')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('extension_workers');
    }
};

