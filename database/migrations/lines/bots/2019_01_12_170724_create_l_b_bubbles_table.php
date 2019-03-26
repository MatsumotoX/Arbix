<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLBBubblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines_bots_bubbles', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('direction')->nullable();
			$table->integer('header_id')->nullable();
			$table->integer('hero_id')->nullable();
			$table->integer('body_id')->nullable();
			$table->integer('footer_id')->nullable();
			$table->integer('header_style_id')->nullable();
			$table->integer('hero_style_id')->nullable();
			$table->integer('body_style_id')->nullable();
			$table->integer('footer_style_id')->nullable();
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
        Schema::dropIfExists('lines_bots_bubbles');
    }
}
