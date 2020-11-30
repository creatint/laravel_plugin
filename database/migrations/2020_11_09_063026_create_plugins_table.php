<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePluginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->increments();
            $table->string('name')->unique();
            $table->string('class')->unique()->nullable();
            $table->string('key')->nullable();
            $table->string('author')->nullable();
            $table->string('author_url')->nullable();
            $table->string('title');
            $table->json('title_i18n')->nullable();
            $table->string('description')->nullable();
            $table->json('description_i18n')->nullable();
            $table->string('version')->nullable();
            $table->json('options')->nullable();
            $table->tinyInteger('status')->index()->default(0)
                ->comment('0:inactive, 1:active 2:develop');
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
        Schema::dropIfExists('plugins');
    }
}
