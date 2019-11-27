<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickingWavesStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picking_waves_state', function (Blueprint $table) {
            $table->primary(['picking_wave_id', 'product_id']);
            $table->unsignedBigInteger('picking_wave_id');
            $table->foreign('picking_wave_id')
                ->references('id')
                ->on('picking_waves')
                ->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
            $table->unsignedBigInteger('desired_qnt');
            $table->unsignedBigInteger('picked_qnt')->nullable();
            $table->boolean('exception')->default(false);
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
        Schema::dropIfExists('picking_waves_state');
    }
}
