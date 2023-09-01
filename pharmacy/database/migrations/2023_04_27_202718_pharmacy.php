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
        Schema::create('pharmacies',function (Blueprint $table){
            $table->id();
            $table->integer('location_id');
            $table->string('pharmacy_address');
            $table->string('pharmacy_name',100);
            $table->string('email')->unique();
            $table->string('password',200);
            $table->string('pharmacy_phone',20);
            $table->string('line_phone',20);
            $table->string('pharmacy_image')->default('img.jpg');
            $table->integer('pharmacy_wallet');
            $table->string('no_facility')->unique();
            $table->string('no_image');
            $table->string('pharmacy_owner',100);
            $table->boolean('is_active')->default(0);
            
            $table->unique(['pharmacy_name','no_facility']);
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