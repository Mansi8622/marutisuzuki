<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckOrderProductPivotTable extends Migration
{
    public function up()
    {
        Schema::create('check_order_product', function (Blueprint $table) {
            $table->unsignedBigInteger('check_order_id');
            $table->foreign('check_order_id', 'check_order_id_fk_10408653')->references('id')->on('check_orders')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'product_id_fk_10408653')->references('id')->on('products')->onDelete('cascade');
        });
    }
}
