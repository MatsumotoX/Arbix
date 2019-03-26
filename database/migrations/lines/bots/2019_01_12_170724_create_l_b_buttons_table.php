<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLBButtonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines_bots_buttons', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->integer('action_id');
			$table->integer('flex')->nullable();
			$table->string('margin')->nullable();
			$table->string('height')->nullable();
			$table->string('style')->nullable();
			$table->string('color')->nullable();
			$table->string('gravity')->nullable();
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
        Schema::dropIfExists('lines_bots_buttons');
    }
}
