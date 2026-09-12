<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGodownProductTable extends Migration
{
    public function up()
    {
        Schema::create('godown_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('godown_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0); // Quantity in each godown
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('godown_product');
    }
}

