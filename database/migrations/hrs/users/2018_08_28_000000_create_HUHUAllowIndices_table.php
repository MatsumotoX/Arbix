<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHUHUAllowIndicesTable extends Migration
{
	public function up()
	{
		Schema::create('hrs_users__HUAllowIndices', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->json('value');
			$table->integer('isActive')->default(1);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('hrs_users_HUAllowIndices');
	}

}