<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservation_sessions', function (Blueprint $table) {
            $table->foreignId('current_game_id')->nullable()->constrained('games')->nullOnDelete();
            $table->decimal('additional_charges', 10, 2)->default(0)->after('duration');
        });
    }

    public function down(): void
    {
        Schema::table('reservation_sessions', function (Blueprint $table) {
            $table->dropForeign(['current_game_id']);
            $table->dropColumn(['current_game_id', 'additional_charges']);
        });
    }
};