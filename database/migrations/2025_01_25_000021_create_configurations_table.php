<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurationsTable extends Migration
{
    public function up()
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('alert_quantity')->nullable();
            $table->decimal('order_handling', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
