<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermConditionsTable extends Migration
{
    public function up()
    {
        Schema::create('term_conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('content');
            $table->date('effective_date')->nullable();
            $table->string('version_number')->nullable();
            $table->string('banner_title')->nullable();
            $table->string('banner_subtitle')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
