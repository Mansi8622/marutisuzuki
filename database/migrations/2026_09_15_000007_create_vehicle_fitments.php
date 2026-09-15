<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subcategory_id');
            $table->foreign('subcategory_id')->references('id')->on('product_categories');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('category_subcategory', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id');
            $table->primary(['category_id', 'subcategory_id']);
        });
        Schema::create('product_fitments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unique(['product_id', 'category_id', 'vehicle_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_fitments');
        Schema::dropIfExists('category_subcategory');
        Schema::dropIfExists('vehicles');
    }
};
