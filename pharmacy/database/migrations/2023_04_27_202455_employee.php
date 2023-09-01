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
        Schema::create('employees',function (Blueprint $table){
            $table->id();
            $table->integer('pharmacy_id');
            $table->string('employee_name',100);
            $table->string('email')->unique();
            $table->string('password',200);
            $table->string('employee_address',100);
            $table->string('employee_phone',20);
            $table->integer('salary');
            $table->integer('wallet')->default(0);
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
