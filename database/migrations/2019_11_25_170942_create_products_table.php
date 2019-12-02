<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->string('product_id')->unique();
            $table->unsignedBigInteger('min_stock');
            $table->unsignedBigInteger('max_stock');
            $table->unsignedBigInteger('stock');
            $table->string('warehouse_section');
            $table->foreign('warehouse_section')
                ->references('section')
                ->on('warehouse')
                ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
