<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('value')->nullable();
        });
        
        // Insert default settings
        \DB::table('settings')->insert([
            ['key' => 'auto_start_session', 'value' => '1'],
            ['key' => 'auto_confirm_reservation', 'value' => '0'],
            ['key' => 'send_reminder_email', 'value' => '1'],
            ['key' => 'reminder_hours_before', 'value' => '2'],
            ['key' => 'theme', 'value' => 'light'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};