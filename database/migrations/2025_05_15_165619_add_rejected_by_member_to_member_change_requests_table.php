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
            $table->string('status_by_admin')->nullable()->default( null); // Replace 'some_existing_column' with the actual column you want to place it after
        });
    }

    public function down(): void
    {
        Schema::table('member_change_requests', function (Blueprint $table) {
            $table->dropColumn('status_by_admin');
        });
    }
};
