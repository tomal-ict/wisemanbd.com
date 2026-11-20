<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSizesToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('products') || Schema::hasColumn('products', 'sizes')) {
            return;
        }
        Schema::table('products', function (Blueprint $table) {
            $table->mediumText('sizes')->nullable()->after('colors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'sizes')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('sizes');
            });
        }
    }
}