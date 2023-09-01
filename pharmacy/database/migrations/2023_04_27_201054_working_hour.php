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
        Schema::create('details',function (Blueprint $table){
            $table->id();
            $table->integer('pharmacy_id');
            $table->string('day',30);
            $table->string('open',100);
            $table->string('close',100);
            $table->boolean('is_duty')->default(0);
            $table->unique(['day','pharmacy_id']);
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
