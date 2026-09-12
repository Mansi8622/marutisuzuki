<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_stock_transfer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('from_godown_id');
            $table->unsignedBigInteger('to_godown_id');
            $table->integer('quantity');
            $table->timestamp('transferred_at')->useCurrent();
            $table->timestamps();
        
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('from_godown_id')->references('id')->on('godowns')->onDelete('cascade');
            $table->foreign('to_godown_id')->references('id')->on('godowns')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_transfer');
    }
};
