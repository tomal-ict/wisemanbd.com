<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSizeStocksTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('size_stocks')) {
            Schema::create('size_stocks', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('product_id');
                $table->string('color')->nullable();
                $table->string('size')->nullable();
                $table->integer('qty')->default(0);
                $table->string('sku')->nullable();
                $table->decimal('price', 10, 2)->nullable();
                $table->timestamps();

                $table->index(['product_id', 'size', 'color']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('size_stocks');
    }
}