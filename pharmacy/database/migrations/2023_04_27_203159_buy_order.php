<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders',function (Blueprint $table){
            $table->id();
            $table->integer('pharmacy_id');
            $table->integer('company_id');
            $table->integer('supplier_id')->nullable();
            $table->date('date');
            $table->string('order_number')->unique();
            $table->integer('order_total')->default(0);
            $table->string('order_description');
            $table->integer('items_count')->default(0);
            $table->boolean('is_accept')->nullable();
            $table->string('mes_company')->nullable();
            $table->boolean('is_sent')->default(0);
            $table->date('send_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
