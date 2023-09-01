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
        Schema::create('corders',function(Blueprint $table){
            $table->id();
            $table->integer('pharmacy_id');
            $table->integer('client_id');
            $table->integer('doctor_id');
            $table->integer('corder_number')->default(1)->unique();
            $table->date('date');
            $table->integer('corder_total')->default(0);
            $table->integer('coitem_number')->default(0);
            $table->string('co_description');
            $table->boolean('is_delivery')->default(0);
            $table->boolean('cdelivery_id')->nullable();
            $table->string('address');
            $table->boolean('is_accept')->nullable();
            $table->string('mes_pharmacy')->nullable();
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
