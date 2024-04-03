<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('orders_links')) {
        Schema::create('orders_links', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('orders_type_id')->constrained('orders_type');
            $table->primary(['order_id', 'orders_type_id']);
        });
    }
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_links');
    }
}
