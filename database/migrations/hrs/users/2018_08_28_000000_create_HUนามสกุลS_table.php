<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHUนามสกุลSTable extends Migration
{
	public function up()
	{
		Schema::create('hrs_users_นามสกุลs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('value');
			$table->boolean('isActive')->default(1);
			$table->integer('createdBy_id');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('hrs_users_นามสกุลs');
	}

}