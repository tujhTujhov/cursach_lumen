<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login');
            $table->string('password');
            $table->string('email');
            $table->string('api_token');
            $table->string('role_id');
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::create('specialities', function (Blueprint $table) {
            $table->id();
            $table->string('speciality');
        });

        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('speciality_id');
            $table->integer('user_id');
            $table->foreign('speciality_id')->references('id')->on('specialities');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('specialities');
        Schema::dropIfExists('users');
    }
}
