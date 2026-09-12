<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCheckGodownsTable extends Migration
{
    public function up()
    {
        Schema::table('check_godowns', function (Blueprint $table) {
            $table->unsignedBigInteger('select_product_id')->nullable();
            $table->foreign('select_product_id', 'select_product_fk_10408638')->references('id')->on('products');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10408642')->references('id')->on('users');
        });
    }
}
