<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMyModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('count_h2');
            $table->string('count_h3');
            $table->string('count_img');
            $table->string('count_video');
            $table->string('count_symbol');
            $table->string('count_word');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('my_models');
    }
}
