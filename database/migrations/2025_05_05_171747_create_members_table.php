<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');

            $table->string('title')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone_number')->nullable();
            
            $table->string('cnic_copy')->nullable();
            $table->string('pmdc_licence_copy')->nullable();
            $table->string('fcps_degree_copy')->nullable();
            
            $table->json('certifications')->nullable(); // [{ name: "", image: "" }, ...]

            $table->json('experience')->nullable(); // [{ hospital: "", years: "" }]
            $table->json('specialities')->nullable(); // array of strings

            $table->text('bio')->nullable();
            $table->string('location')->nullable();

            $table->json('social_links')->nullable(); // { facebook: "", instagram: "", ... }

            $table->json('availability')->nullable(); // { monday: { open: "", close: "" }, ... }

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
};
