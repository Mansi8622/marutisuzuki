<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutUssTable extends Migration
{
    public function up()
    {
        Schema::create('about_uss', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('contant');
            $table->longText('mission_statement')->nullable();
            $table->longText('vision_statement')->nullable();
            $table->longText('team_section_content')->nullable();
            $table->longText('achievements_content')->nullable();
            $table->string('banner_title')->nullable();
            $table->string('banner_subtitle')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
