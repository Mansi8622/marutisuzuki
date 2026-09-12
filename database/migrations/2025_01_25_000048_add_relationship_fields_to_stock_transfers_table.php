<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToStockTransfersTable extends Migration
{
    public function up()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('select_user_id')->nullable();
            $table->foreign('select_user_id', 'select_user_fk_10408645')->references('id')->on('users');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10408649')->references('id')->on('users');
        });
    }
}
