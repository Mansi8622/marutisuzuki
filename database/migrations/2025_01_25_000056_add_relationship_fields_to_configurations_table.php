<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToConfigurationsTable extends Migration
{
    public function up()
    {
        Schema::table('configurations', function (Blueprint $table) {
            $table->unsignedBigInteger('defaulat_tax_id')->nullable();
            $table->foreign('defaulat_tax_id', 'defaulat_tax_fk_10408854')->references('id')->on('taxes');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10408859')->references('id')->on('users');
        });
    }
}
