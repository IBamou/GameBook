<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('session_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('reservation_sessions')->cascadeOnDelete();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->decimal('price_at_time', 10, 2);
            $table->boolean('is_active')->default(false);
            $table->timestamp('returned_at')->nullable();
            $table->timestamp('added_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_games');
    }
};
