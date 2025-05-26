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
        Schema::create('member_change_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_change_request_id')->constrained('member_change_requests')->onDelete('cascade');
            $table->json('data'); // All updated fields: { "bio": "...", "title": "...", "testimonials": [...] }
            $table->timestamps();
            $table->softDeletes();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_change_request_items');
    }
};
