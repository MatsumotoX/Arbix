<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLBImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines_bots_images', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('url');
			$table->integer('flex')->nullable();
			$table->string('margin')->nullable();
			$table->string('align')->nullable();
			$table->string('gravity')->nullable();
			$table->string('size')->nullable();
			$table->string('aspectRatio')->nullable();
			$table->string('aspectMode')->nullable();
			$table->string('backgroundColor')->nullable();
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
        Schema::dropIfExists('lines_bots_images');
    }
}
