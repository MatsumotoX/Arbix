<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLSHomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landingpages_settings_homes', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name')->unique();
			$table->string('header_EN')->nullable();
			$table->string('header_TH')->nullable();
			$table->string('content_EN')->nullable();
			$table->string('content_TH')->nullable();
			$table->boolean('isSelect')->default(1);
			$table->boolean('isActive')->default(1);
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
        Schema::dropIfExists('landingpages_settings_homes');
    }
}
