<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOurStocksTable extends Migration
{
    public function up()
    {
        Schema::create('our_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quantity_available')->nullable();
            $table->string('sku')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
