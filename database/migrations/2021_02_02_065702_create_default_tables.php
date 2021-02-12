<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
            $table->string('name');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login');
            $table->string('password');
            $table->string('email');
            $table->string('api_token');
            $table->bigInteger('role_id');
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::create('time_intervals',function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('specialities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('speciality_pic_url')->nullable()->default("pepe.png");
        });

        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('middlename')->nullable()->default(null);
            $table->bigInteger('speciality_id');
            $table->bigInteger('user_id');
            $table->string('avatar_url')->default('default.png');

            $table->foreign('speciality_id')->references('id')->on('specialities');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('middlename')->nullable()->default(null);
            $table->bigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('doctor_id');
            $table->date('date');

            $table->foreign('doctor_id')->references('id')->on('doctors');
        });

        Schema::create('custom_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('schedule_id');
            $table->bigInteger('nonwork_time_interval_id');

            $table->foreign('nonwork_time_interval_id')->references('id')->on('schedules');
            $table->foreign('schedule_id')->references('id')->on('time_intervals');});

        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('doctor_id');
            $table->bigInteger('patient_id');
            $table->enum('status', ['wait', 'finished'])->default('wait');
            $table->bigInteger('time_interval_id');
            $table->bigInteger('schedule_id');

            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('time_interval_id')->references('id')->on('time_intervals');
            $table->foreign('schedule_id')->references('id')->on('schedules');
        });

        /** заполнение некоторых таблиц */

        /** добавление ролей */
        DB::table('roles')->insert([['name' => "Доктор"], ['name' => "Пациент"]]);

        /** добавление пользователей */
        DB::table('users')->insert([
            [
                'login' => "doctor",
                'password' => "doctor",
                'email' => "doctor@mail.com",
                'api_token' => Str::random(),
                'role_id' => 1,
            ],
            [
                'login' => "patient",
                'password' => "patient",
                'email' => "patient@mail.com",
                'api_token' => Str::random(),
                'role_id' => 2,
            ],

        ]);

        /** добавление временных интервалов */
        $timeIntervals = []; $i = 0; $dateTime = new DateTime('2011-11-17 09:00');
        while($dateTime->format('H:i') != '18:00'){
            $timeIntervals[$i]['name'] = $dateTime->format('H:i');
            $dateTime->modify("+15 minutes");
            $i++;
        }
        DB::table('time_intervals')->insert($timeIntervals);

        /** добавление специальности */
        DB::table('specialities')->insert([['name' => "Ортопед"], ['name' => "Терапевт"], ['name' => "Пепе"]]);

        /** добавление врачей */
        DB::table('doctors')->insert([
            [
                'name' => 'Михаил',
                'surname' => 'Геринович',
                'middlename' => 'Сергеевич',
                'speciality_id' => 1,
                'user_id' => 1,
            ]
        ]);

        /** добавление пациентов */
        DB::table('patients')->insert([
            [
                'name' => 'Егор',
                'surname' => 'Семенков',
                'middlename' => 'Леонидович',
                'user_id' => 2,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visits');
        Schema::dropIfExists('custom_schedules');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('specialities');
        Schema::dropIfExists('time_intervals');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
}
