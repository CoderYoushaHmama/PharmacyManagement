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
        Schema::create('wastes',function(Blueprint $table){
            $table->id();
            $table->integer('pharmacy_id');
            $table->integer('employee_id')->nullable();
            $table->string('wastes_number')->unique();
            $table->date('date');
            $table->string('w_descriptions');
            $table->integer('waste_total');
            $table->integer('iwaste_number');
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
