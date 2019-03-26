<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLBFlexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines_bots_flexes', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name')->unique();
			$table->string('altText');
			$table->json('contents');
			$table->integer('quickreply_id')->nullable();
			$table->string('altTextSpecial')->nullable();
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
        Schema::dropIfExists('lines_bots_flexes');
    }
}
