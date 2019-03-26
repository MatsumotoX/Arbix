<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLBBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines_bots_boxes', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('layout');
			$table->integer('component_id');
			$table->integer('flex')->nullable();
			$table->string('spacing')->nullable();
			$table->string('margin')->nullable();
			$table->integer('action_id')->nullable();
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
        Schema::dropIfExists('lines_bots_boxes');
    }
}
