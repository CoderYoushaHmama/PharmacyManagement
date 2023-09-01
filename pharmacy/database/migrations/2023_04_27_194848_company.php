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
        Schema::create('companies',function (Blueprint $table){
            $table->id();
            $table->integer('location_id');
            $table->string('company_name')->unique();
            $table->string('company_phone',20);
            $table->string('line_phone',20);
            $table->string('company_address');
            $table->string('email',100)->unique();
            $table->string('password',200);
            $table->string('company_owner',100);
            $table->string('establishment_no')->unique();
            $table->string('company_image')->default('img.jpg');
            $table->string('es_image');
            $table->boolean('is_active')->default(0);
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
