<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_xx_xx_create_verifications_table.php
public function up()
{
    Schema::create('verifications', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email');
        $table->string('number');
        $table->string('image'); // profile image
        $table->string('aadhar_image');
        $table->string('pan_image');
        $table->string('reseller_code');
        $table->enum('verification_status', ['verified', 'pending', 'rejected'])->default('pending');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};
