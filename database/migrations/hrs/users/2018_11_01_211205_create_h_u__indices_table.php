<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHUIndicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrs_users__indices', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name')->unique();
			$table->string('type');
			$table->integer('group_id');
			$table->boolean('isUnique');
			$table->boolean('hasMultiple');
			$table->boolean('hasDate');
			$table->string('relation')->nullable();
			$table->boolean('isSpecial');
			$table->boolean('canCreate')->default(1);
			$table->integer('digit')->nullable();
			$table->integer('decimal')->nullable();
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
        Schema::dropIfExists('hrs_users__indices');
    }
}
