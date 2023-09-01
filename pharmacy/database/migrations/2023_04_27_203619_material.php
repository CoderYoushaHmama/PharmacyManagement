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
        Schema::create('materials',function (Blueprint $table){
            $table->id();
            $table->integer('company_id');
            $table->integer('type_id');
            $table->string('material_name',100)->unique();
            $table->string('scientific_name',100)->unique();
            $table->string('material_image');
            $table->integer('pp');
            $table->integer('ps');
            $table->string('qr_code');
            $table->string('license_image');
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
