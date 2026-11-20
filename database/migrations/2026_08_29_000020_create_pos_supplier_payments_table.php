<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosSupplierPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('pos_supplier_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_no', 50);
            $table->date('payment_date');
            $table->unsignedInteger('supplier_id');
            $table->string('payment_method', 50)->nullable();
            $table->double('amount', 20, 2)->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pos_supplier_payments');
    }
}
