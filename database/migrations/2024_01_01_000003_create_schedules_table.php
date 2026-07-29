<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('train_id')->constrained()->onDelete('cascade');
            $table->foreignId('origin_station_id')->constrained('stations')->onDelete('cascade');
            $table->foreignId('destination_station_id')->constrained('stations')->onDelete('cascade');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->string('duration');
            $table->decimal('price', 12, 2);
            $table->json('available_days')->nullable(); // ['Senin', 'Selasa', ...]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
