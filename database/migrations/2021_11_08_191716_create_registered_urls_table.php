<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisteredUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registered_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('original_url');
            $table->string('short_url');
            $table->bigInteger('access_counter')->default(0);
            $table->json('access_infos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registered_urls');
    }
}
