<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('order_attachments')) {
        Schema::create('order_attachments', function (Blueprint $table) {
                $table->foreignId('order_reply_id')->constrained('order_replies');
                $table->foreignId('file_id')->constrained('files');
                $table->primary(['order_reply_id', 'file_id']);
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
        Schema::dropIfExists('order_attachments');
    }
}
