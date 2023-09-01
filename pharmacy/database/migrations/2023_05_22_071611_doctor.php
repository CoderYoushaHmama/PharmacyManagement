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
        Schema::create('doctors',function (Blueprint $table){
            $table->id();
            $table->integer('location_id');
            $table->string('doctor_address');
            $table->string('doctor_name',100);
            $table->string('email',100)->unique();
            $table->string('password',200);
            $table->string('doctor_phone',20);
            $table->string('doctor_description',200);
            $table->string('doctor_image',200);
            $table->string('d_image');
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
