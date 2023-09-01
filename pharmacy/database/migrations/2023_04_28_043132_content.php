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
        Schema::create('contents',function(Blueprint $table){
            $table->id();
            $table->integer('pharmacy_id');
            $table->integer('material_id');
            $table->integer('batch_id');
            $table->integer('quantity');
            $table->integer('min')->default(0);
            $table->integer('max');
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
