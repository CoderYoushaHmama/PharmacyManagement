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
        Schema::create('cdelivery',function (Blueprint $table){
            $table->id();
            $table->integer('location_id');
            $table->string('cdelivery_address',100);
            $table->string('cdelivery_name',100);
            $table->string('cdelivery_phone',20);
            $table->string('cdelivery_line_phone',20);
            $table->string('cdelivery_email',100);
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
