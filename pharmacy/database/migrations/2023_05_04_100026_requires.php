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
        Schema::create('requires',function(Blueprint $table){
            $table->id();
            $table->integer('material_id');
            $table->integer('employee_id');
            $table->integer('pharmacy_id');
            $table->date('date');
            $table->integer('quantity');
            $table->string('description');
            $table->string('require_number')->unique();
            $table->boolean('is_accept')->default(0);
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
