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
        Schema::create('bills',function (Blueprint $table){
            $table->id();
            $table->integer('pharmacy_id');
            $table->integer('employee_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('doctor_id')->nullable();
            $table->string('client_name')->nullable();
            $table->date('date');
            $table->string('bill_number')->unique();
            $table->integer('bill_total')->default(0);
            $table->integer('bitem_number')->default(0);
            $table->boolean('is_delivery')->default(0);
            $table->integer('delivery_cost')->default(0);
            $table->integer('cdelivery_id')->nullable();
            $table->boolean('is_return')->default(0);
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
