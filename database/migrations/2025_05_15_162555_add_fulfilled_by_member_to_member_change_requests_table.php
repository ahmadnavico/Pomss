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
        Schema::table('member_change_requests', function (Blueprint $table) {
            $table->boolean('fulfilled_by_member')->default(false); // Replace 'some_existing_column' with the actual column you want to place it after
        });
    }

    public function down(): void
    {
        Schema::table('member_change_requests', function (Blueprint $table) {
            $table->dropColumn('fulfilled_by_member');
        });
    }
};
