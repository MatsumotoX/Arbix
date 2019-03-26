<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLBRichMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines_bots_rich_menus', function (Blueprint $table) {
            $table->increments('id');
			$table->string('richMenuId')->nullable();
			$table->string('name');
			$table->string('size');
			$table->json('area_id')->nullable();
			$table->string('chatBarText');
			$table->boolean('selected');
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
        Schema::dropIfExists('lines_bots_rich_menus');
    }
}
