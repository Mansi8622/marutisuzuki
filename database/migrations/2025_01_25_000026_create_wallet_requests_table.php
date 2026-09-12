<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('wallet_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status')->nullable();
            $table->decimal('welcome_amount', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
