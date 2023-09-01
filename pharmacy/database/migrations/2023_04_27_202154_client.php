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
        Schema::create('clients',function (Blueprint $table){
            $table->id();
            $table->integer('location_id');
            $table->string('client_name',100);
            $table->string('email',100)->unique();
            $table->string('password',200);
            $table->string('client_phone',20);
            $table->string('client_description',200);
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
