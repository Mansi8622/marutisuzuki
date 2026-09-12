<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('verifications', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('id');
    
            // If you want to enforce foreign key (recommended)
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verifications', function (Blueprint $table) {
            //
        });
    }
};
