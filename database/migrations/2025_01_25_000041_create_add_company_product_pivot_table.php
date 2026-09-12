<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddCompanyProductPivotTable extends Migration
{
    public function up()
    {
        Schema::create('add_company_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'product_id_fk_10408618')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('add_company_id');
            $table->foreign('add_company_id', 'add_company_id_fk_10408618')->references('id')->on('add_companies')->onDelete('cascade');
        });
    }
}
