<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('meta_key');
            $table->text('meta_value');
            $table->timestamps();
        });
        Schema::create('characters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('job_title');
            $table->integer('mood')->nullable();
            $table->text('watch_counter')->nullable();
            $table->boolean('work')->nullable();
            $table->text('actions');
            $table->timestamps();
        });
        Schema::create('actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('action_name');
            $table->string('towards')->nullable();
            $table->string('watching')->nullable();
            $table->string('consequence');
            $table->timestamps();
        });
        Schema::create('moods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('hierarchy');
            $table->string('mood_name');
            $table->timestamps();
        });
        Schema::create('watchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('characters_id');
            $table->string('action_name');
            $table->string('last_updated')->nullable();
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
        Schema::dropIfExists('states');
        Schema::dropIfExists('characters');
        Schema::dropIfExists('actions');
        Schema::dropIfExists('moods');
        Schema::dropIfExists('meta');
    }
}
