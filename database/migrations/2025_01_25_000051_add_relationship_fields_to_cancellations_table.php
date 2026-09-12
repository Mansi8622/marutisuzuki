<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCancellationsTable extends Migration
{
    public function up()
    {
        Schema::table('cancellations', function (Blueprint $table) {
            $table->unsignedBigInteger('order_number_id')->nullable();
            $table->foreign('order_number_id', 'order_number_fk_10408758')->references('id')->on('check_orders');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_10408768')->references('id')->on('products');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10408764')->references('id')->on('users');
        });
    }
}
