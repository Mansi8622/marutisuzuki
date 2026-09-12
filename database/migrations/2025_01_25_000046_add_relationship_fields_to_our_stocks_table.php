<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToOurStocksTable extends Migration
{
    public function up()
    {
        Schema::table('our_stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('select_product_id')->nullable();
            $table->foreign('select_product_id', 'select_product_fk_10408631')->references('id')->on('products');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10408636')->references('id')->on('users');
        });
    }
}
