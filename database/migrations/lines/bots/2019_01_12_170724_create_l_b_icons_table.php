<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLBIconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines_bots_icons', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('url');
			$table->string('margin')->nullable();
			$table->string('size')->nullable();
			$table->string('aspectRatio')->nullable();
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
        Schema::dropIfExists('lines_bots_icons');
    }
}
