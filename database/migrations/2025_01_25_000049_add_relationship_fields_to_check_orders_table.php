<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCheckOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('check_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('select_user_id')->nullable();
            $table->foreign('select_user_id', 'select_user_fk_10408652')->references('id')->on('users');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10408667')->references('id')->on('users');
        });
    }
}
