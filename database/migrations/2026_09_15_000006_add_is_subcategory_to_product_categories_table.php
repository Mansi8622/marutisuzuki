<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddIsSubcategoryToProductCategoriesTable extends Migration
{
    public function up()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->boolean('is_subcategory')->default(false)->after('has_subcategories');
        });
        DB::table('product_categories')->whereNotNull('parent_id')->update(['is_subcategory' => true]);
    }

    public function down()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn('is_subcategory');
        });
    }
}
