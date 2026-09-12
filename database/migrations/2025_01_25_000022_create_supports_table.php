<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportsTable extends Migration
{
    public function up()
    {
        Schema::create('supports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('live_chat')->nullable();
            $table->string('support_phone')->nullable();
            $table->string('toll_free_number')->nullable();
            $table->string('support_email')->nullable();
            $table->longText('refund_ploicy')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
