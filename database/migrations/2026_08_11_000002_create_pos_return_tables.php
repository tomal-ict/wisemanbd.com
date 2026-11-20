<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosReturnTables extends Migration
{
    public function up()
    {
        Schema::create('pos_returns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('return_no', 50);
            $table->unsignedInteger('sale_id');
            $table->date('return_date');
            $table->unsignedInteger('customer_id')->default(1);
            $table->double('discount_amount', 20, 2)->default(0);
            $table->double('subtotal', 20, 2)->default(0);
            $table->double('grand_total', 20, 2)->default(0);
            $table->double('paid_amount', 20, 2)->default(0);
            $table->string('payment_method', 50)->nullable();
            $table->text('note')->nullable();
            $table->string('status', 20)->default('Accepted');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_return_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('return_id');
            $table->unsignedInteger('sale_item_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->string('name', 200)->nullable();
            $table->double('qty', 20, 2)->default(0);
            $table->double('price', 20, 2)->default(0);
            $table->double('purchase_price', 20, 2)->default(0);
            $table->double('total', 20, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('pos_purchase_returns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('return_no', 50);
            $table->unsignedInteger('purchase_id');
            $table->date('return_date');
            $table->unsignedInteger('supplier_id')->default(1);
            $table->double('discount_amount', 20, 2)->default(0);
            $table->double('subtotal', 20, 2)->default(0);
            $table->double('grand_total', 20, 2)->default(0);
            $table->double('paid_amount', 20, 2)->default(0);
            $table->string('payment_method', 50)->nullable();
            $table->text('note')->nullable();
            $table->string('status', 20)->default('Accepted');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_purchase_return_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('purchase_return_id');
            $table->unsignedInteger('purchase_item_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->string('name', 200)->nullable();
            $table->double('qty', 20, 2)->default(0);
            $table->double('unit_cost', 20, 2)->default(0);
            $table->double('total', 20, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pos_purchase_return_items');
        Schema::dropIfExists('pos_purchase_returns');
        Schema::dropIfExists('pos_return_items');
        Schema::dropIfExists('pos_returns');
    }
}