<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLBTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines_bots_texts', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('type');
			$table->string('text');
			$table->integer('flex')->nullable();
			$table->string('margin')->nullable();
			$table->string('size')->nullable();
			$table->string('align')->nullable();
			$table->string('gravity')->nullable();
			$table->string('wrap')->nullable();
			$table->integer('maxLines')->nullable();
			$table->string('weight')->nullable();
			$table->string('color')->nullable();
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
        Schema::dropIfExists('lines_bots_texts');
    }
}
