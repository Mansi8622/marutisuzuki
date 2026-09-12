<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('item_code')->nullable()->unique();
            $table->string('hsn_code')->nullable()->unique();
            
            // Add relation to godowns table
            $table->unsignedBigInteger('godown_id')->nullable();
            $table->foreign('godown_id')->references('id')->on('godowns')->onDelete('set null');

            $table->longText('description')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->integer('discount')->nullable();
            $table->decimal('price_1', 15, 2)->nullable();
            $table->decimal('rate_2', 15, 2)->nullable();
            $table->decimal('rate_3', 15, 2)->nullable();
            $table->string('sku')->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
