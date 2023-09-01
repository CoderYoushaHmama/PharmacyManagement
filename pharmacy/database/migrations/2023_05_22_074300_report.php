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
        Schema::create('reports',function (Blueprint $table){
            $table->id();
            $table->boolean('is_require')->default(0);
            $table->boolean('is_order')->default(0);
            $table->boolean('is_bill')->default(0);
            $table->boolean('is_corder')->default(0);
            $table->boolean('is_waste')->default(0);
            $table->integer('company_id')->nullable();
            $table->integer('pharmacy_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('doctor_id')->nullable();
            $table->date('from');
            $table->date('to');
            $table->integer('total')->default(0);
            $table->date('date');
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
