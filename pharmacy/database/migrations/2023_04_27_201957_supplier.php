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
        Schema::create('suppliers',function (Blueprint $table){
            $table->id();
            $table->integer('location_id');
            $table->string('supplier_address');
            $table->integer('company_id');
            $table->string('supplier_name',100);
            $table->string('supplier_image',150);
            $table->string('supplier_phone',200);
            $table->string('supplier_description',200);
            $table->integer('national_number')->unique();
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
