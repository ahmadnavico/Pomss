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
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('event_type', ['virtual', 'physical'])->nullable();
            $table->enum('event_for', ['public', 'members'])->nullable();
            $table->enum('event_cost', ['free', 'paid'])->nullable();
            $table->string('meeting_link')->nullable();
            $table->string('venue')->nullable();
            $table->string('entry_code')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'event_type',
                'event_for',
                'event_cost',
                'meeting_link',
                'venue',
                'entry_code',
            ]);
        });
    }
};
