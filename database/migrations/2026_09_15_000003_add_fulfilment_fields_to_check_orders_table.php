<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFulfilmentFieldsToCheckOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('check_orders', function (Blueprint $table) {
            $table->decimal('confirmed_amount', 15, 2)->nullable()->after('total_amount');
            $table->decimal('credit_refund_amount', 15, 2)->default(0)->after('confirmed_amount');
            $table->text('fulfilment_note')->nullable()->after('notes');
            $table->unsignedBigInteger('offer_id')->nullable()->after('payment_method');
            $table->decimal('offer_discount_amount', 15, 2)->default(0)->after('offer_id');
        });
    }

    public function down()
    {
        Schema::table('check_orders', function (Blueprint $table) {
            $table->dropColumn(['confirmed_amount', 'credit_refund_amount', 'fulfilment_note', 'offer_id', 'offer_discount_amount']);
        });
    }
}
