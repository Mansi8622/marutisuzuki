<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasSubcategoriesToProductCategoriesTable extends Migration
{
    public function up()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->boolean('has_subcategories')->default(false)->after('parent_id');
        });
    }

    public function down()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn('has_subcategories');
        });
    }
}
