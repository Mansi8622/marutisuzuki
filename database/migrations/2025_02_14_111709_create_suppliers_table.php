<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('state');
            $table->string('city');
            $table->string('pin_code');
            $table->text('full_address');
            $table->string('gst_number')->unique();
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->string('gst_document')->nullable(); // File upload ke liye
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};
