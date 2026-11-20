<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockTrackingToPosItems extends Migration
{
    public function up()
    {
        Schema::table('pos_sale_items', function (Blueprint $table) {
            $table->unsignedInteger('stock_id')->nullable()->after('product_id');
            $table->string('variant', 200)->nullable()->after('name');
        });

        Schema::table('pos_hold_items', function (Blueprint $table) {
            $table->unsignedInteger('stock_id')->nullable()->after('product_id');
            $table->string('variant', 200)->nullable()->after('name');
        });

        Schema::table('pos_return_items', function (Blueprint $table) {
            $table->unsignedInteger('stock_id')->nullable()->after('product_id');
            $table->string('variant', 200)->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('pos_return_items', function (Blueprint $table) {
            $table->dropColumn(['stock_id', 'variant']);
        });

        Schema::table('pos_hold_items', function (Blueprint $table) {
            $table->dropColumn(['stock_id', 'variant']);
        });

        Schema::table('pos_sale_items', function (Blueprint $table) {
            $table->dropColumn(['stock_id', 'variant']);
        });
    }
}
