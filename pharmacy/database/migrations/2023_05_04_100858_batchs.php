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
        Schema::create('batchs',function(Blueprint $table){
            $table->id();
            $table->integer('material_id');
            $table->integer('batch');
            $table->integer('quantity');
            $table->date('expiry_date');
            $table->string('description');
            $table->boolean('is_active')->default(0);
            $table->unique(['material_id','batch']);
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
