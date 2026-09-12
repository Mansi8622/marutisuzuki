<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOurStockStockTransferPivotTable extends Migration
{
    public function up()
    {
        Schema::create('our_stock_stock_transfer', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_transfer_id');
            $table->foreign('stock_transfer_id', 'stock_transfer_id_fk_10408644')->references('id')->on('stock_transfers')->onDelete('cascade');
            $table->unsignedBigInteger('our_stock_id');
            $table->foreign('our_stock_id', 'our_stock_id_fk_10408644')->references('id')->on('our_stocks')->onDelete('cascade');
        });
    }
}
