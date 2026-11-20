<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVariantToPosPurchaseItems extends Migration
{
    public function up()
    {
        Schema::table('pos_purchase_items', function (Blueprint $table) {
            $table->string('variant', 200)->nullable()->after('name');
            $table->string('color', 200)->nullable()->after('variant');
            $table->string('size', 200)->nullable()->after('color');
        });
    }

    public function down()
    {
        Schema::table('pos_purchase_items', function (Blueprint $table) {
            $table->dropColumn(['variant', 'color', 'size']);
        });
    }
}
