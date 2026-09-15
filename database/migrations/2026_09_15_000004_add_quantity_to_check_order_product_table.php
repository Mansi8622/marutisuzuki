<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityToCheckOrderProductTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('check_order_product', 'quantity')) {
            return;
        }

        Schema::table('check_order_product', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->default(1)->after('product_id');
        });
    }

    public function down()
    {
        if (! Schema::hasColumn('check_order_product', 'quantity')) {
            return;
        }

        Schema::table('check_order_product', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
}
