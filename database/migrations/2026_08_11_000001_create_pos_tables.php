<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosTables extends Migration
{
    /**
     * POS Manager module tables (native Laravel re-implementation
     * of the standalone POS system). Inventory is shared with the
     * e-commerce via product_stocks / products.pos_cost_price.
     */
    public function up()
    {
        Schema::create('pos_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_code', 30)->nullable();
            $table->string('name', 150);
            $table->string('mobile', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('address')->nullable();
            $table->double('opening_balance', 20, 2)->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('pos_suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('supplier_code', 30)->nullable();
            $table->string('name', 150);
            $table->string('mobile', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('address')->nullable();
            $table->double('opening_balance', 20, 2)->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('pos_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slip_no', 50);
            $table->date('sale_date');
            $table->unsignedInteger('customer_id')->default(1);
            $table->string('discount_type', 20)->nullable();
            $table->double('discount_amount', 20, 2)->default(0);
            $table->double('other_charges', 20, 2)->default(0);
            $table->double('subtotal', 20, 2)->default(0);
            $table->double('round_off', 20, 2)->default(0);
            $table->double('grand_total', 20, 2)->default(0);
            $table->double('paid_amount', 20, 2)->default(0);
            $table->double('change_return', 20, 2)->default(0);
            $table->string('payment_method', 50)->nullable();
            $table->text('note')->nullable();
            $table->string('status', 20)->default('Final');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_sale_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sale_id');
            $table->unsignedBigInteger('product_id');
            $table->string('name', 200)->nullable();
            $table->double('qty', 20, 2)->default(0);
            $table->double('price', 20, 2)->default(0);
            $table->double('discount_amount', 20, 2)->default(0);
            $table->double('purchase_price', 20, 2)->default(0);
            $table->double('total', 20, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('pos_sale_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sale_id');
            $table->date('payment_date');
            $table->string('payment_method', 50)->nullable();
            $table->double('amount', 20, 2)->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('purchase_no', 50);
            $table->date('purchase_date');
            $table->unsignedInteger('supplier_id')->default(1);
            $table->string('discount_type', 20)->nullable();
            $table->double('discount_amount', 20, 2)->default(0);
            $table->double('other_charges', 20, 2)->default(0);
            $table->double('subtotal', 20, 2)->default(0);
            $table->double('round_off', 20, 2)->default(0);
            $table->double('grand_total', 20, 2)->default(0);
            $table->double('paid_amount', 20, 2)->default(0);
            $table->string('payment_method', 50)->nullable();
            $table->text('note')->nullable();
            $table->string('status', 20)->default('Pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_purchase_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('purchase_id');
            $table->unsignedBigInteger('product_id');
            $table->string('name', 200)->nullable();
            $table->double('qty', 20, 2)->default(0);
            $table->double('unit_cost', 20, 2)->default(0);
            $table->double('discount_amount', 20, 2)->default(0);
            $table->double('total', 20, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('pos_purchase_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('purchase_id');
            $table->date('payment_date');
            $table->string('payment_method', 50)->nullable();
            $table->double('amount', 20, 2)->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_expense_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 30)->nullable();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('pos_expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('expense_code', 50)->nullable();
            $table->unsignedInteger('category_id')->default(0);
            $table->date('expense_date');
            $table->string('expense_for', 150)->nullable();
            $table->string('reference_no', 50)->nullable();
            $table->double('amount', 20, 2)->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_holds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hold_no', 50);
            $table->unsignedInteger('customer_id')->default(1);
            $table->double('discount_amount', 20, 2)->default(0);
            $table->double('subtotal', 20, 2)->default(0);
            $table->double('grand_total', 20, 2)->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_hold_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hold_id');
            $table->unsignedBigInteger('product_id');
            $table->string('name', 200)->nullable();
            $table->double('qty', 20, 2)->default(0);
            $table->double('price', 20, 2)->default(0);
            $table->double('discount_amount', 20, 2)->default(0);
            $table->double('total', 20, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('pos_stock_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('product_id');
            $table->date('entry_date');
            $table->double('qty', 20, 2)->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->double('pos_cost_price', 20, 2)->default(0)->after('current_stock');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pos_stock_entries');
        Schema::dropIfExists('pos_hold_items');
        Schema::dropIfExists('pos_holds');
        Schema::dropIfExists('pos_expenses');
        Schema::dropIfExists('pos_expense_categories');
        Schema::dropIfExists('pos_purchase_payments');
        Schema::dropIfExists('pos_purchase_items');
        Schema::dropIfExists('pos_purchases');
        Schema::dropIfExists('pos_sale_payments');
        Schema::dropIfExists('pos_sale_items');
        Schema::dropIfExists('pos_sales');
        Schema::dropIfExists('pos_suppliers');
        Schema::dropIfExists('pos_customers');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('pos_cost_price');
        });
    }
}