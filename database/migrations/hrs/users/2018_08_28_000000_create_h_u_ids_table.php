<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHUIdsTable extends Migration
{
	public function up()
	{
		Schema::create('hrs_users__ids', function (Blueprint $table) {
			$table->increments('id');
			$table->string('value')->unique();
			$table->integer('isActive')->default(1);
			$table->integer('user_id')->nullable();
			$table->integer('createdBy_id');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('hrs_users__users');
	}

}