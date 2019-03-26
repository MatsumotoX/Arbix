<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLBActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines_bots_actions', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('type');
			$table->string('label')->nullable();
			$table->string('data')->nullable();
			$table->string('displayText')->nullable();
			$table->string('text')->nullable();
			$table->string('uri')->nullable();
			$table->string('mode')->nullable();
			$table->string('initial')->nullable();
			$table->string('max')->nullable();
			$table->string('min')->nullable();
			$table->string('special')->nullable();
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
        Schema::dropIfExists('lines_bots_actions');
    }
}
