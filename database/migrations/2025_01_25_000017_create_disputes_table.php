<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisputesTable extends Migration
{
    public function up()
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable();
            $table->string('refund_request')->nullable();
            $table->string('response')->nullable();
            $table->string('last_update')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
