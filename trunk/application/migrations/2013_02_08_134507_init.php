<?php

class Init {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
    {
        //create school table
        Schema::create('schools', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('name', 1000);
            $table->text('address');
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('zip', 20);
            $table->string('senderId', 45);
            $table->string('contactPerson', 400);
            $table->string('contactMobile', 45);
            $table->string('code', 100);
            $table->timestamps();
        });


        //create user table
        Schema::create('users', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('email', 1000);
            $table->string('password', 1000);
            $table->string('mobile', 50);
            $table->string('emailVerificationCode', 100)->nullable();
            $table->string('mobileVerificationCode', 50)->nullable();
            $table->integer('schoolId')->unsigned();
            $table->foreign('schoolId')->references('id')->on('schools');
            $table->string('forgottenPasswordCode', 100)->nullable()->default(NULL);
            $table->integer('isVerified')->default(false);
            $table->integer('isDeactivated')->default(false);
            $table->integer('isDeleted')->default(false);
            $table->integer('reactivateCode')->nullable()->default(NULL);
            $table->timestamps();
        });

        //create roles table
        Schema::create('roles', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('name', 200);
            $table->timestamps();
        });


        //create Users Roles table
        Schema::create('role_user', function ($table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->timestamps();
        });

        //create students table
        Schema::create('students', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('name', 500);
            $table->string('email', 1000);
            $table->string('motherName', 300);
            $table->string('fatherName', 300);
            $table->string('mobile1', 45);
            $table->string('mobile2', 45);
            $table->string('mobile3', 45);
            $table->string('mobile4', 45);
            $table->string('mobile5', 45);
            $table->date('dob');
            $table->string('classStandard', 45);
            $table->string('classSection', 45);
            $table->string('morningBusRoute', 45);
            $table->string('eveningBusRoute', 45);
            $table->string('code', 100);
            $table->string('sex', 45);
            $table->integer('schoolId')->unsigned();
            $table->foreign('schoolId')->references('id')->on('schools');
            $table->timestamps();

        });


        //create teachers table
        Schema::create('teachers', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('name', 500);
            $table->string('email', 1000);
            $table->string('mobile1', 45);
            $table->string('mobile2', 45);
            $table->string('mobile3', 45);
            $table->string('mobile4', 45);
            $table->string('mobile5', 45);
            $table->date('dob');
            $table->string('department', 45);
            $table->string('morningBusRoute', 45);
            $table->string('eveningBusRoute', 45);
            $table->string('code', 100);
            $table->string('sex', 45);
            $table->integer('schoolId')->unsigned();
            $table->foreign('schoolId')->references('id')->on('schools');
            $table->timestamps();

        });


        //create SMS Transaction Table
        Schema::create('smsTransactions', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('mobile', 45);
            $table->text('message');
            $table->string('status', 45);
            $table->integer('credits');
            $table->integer('studentId');
            $table->integer('teacherId');
            $table->integer('senderId');
            $table->integer('userId')->unsigned();
            $table->foreign('userId')->references('id')->on('users');
            $table->timestamps();

        });


        //create Application SMS Transaction Table
        Schema::create('appSmsTransactions', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('mobile', 45);
            $table->text('message');
            $table->string('status', 45);
            $table->integer('credits');
            $table->integer('senderId');
            $table->integer('userId')->unsigned();
            $table->foreign('userId')->references('id')->on('users');
            $table->timestamps();

        });


        //create SMS credits table
        Schema::create('smsCredits', function ($table) {
            $table->increments('id')->unsigned();
            $table->integer('credits');
            $table->integer('schoolId')->unsigned();
            $table->foreign('schoolId')->references('id')->on('schools');
            $table->timestamps();

        });


        //create transaction table
        Schema::create('transactions', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('orderId', 64);
            $table->integer('smsCredits');
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 3, 2);
            $table->decimal('grossAmount', 10, 2);
            $table->integer('schoolId')->unsigned();
            $table->foreign('schoolId')->references('id')->on('schools');
            $table->timestamps();

        });

    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->drop_foreign('users_schoolId_foreign');
        });

        Schema::table('role_user', function ($table) {
            $table->drop_foreign('role_user_user_id_foreign');
            $table->drop_foreign('role_user_role_id_foreign');
        });

        Schema::table('students', function ($table) {
            $table->drop_foreign('students_schoolId_foreign');
        });

        Schema::table('teachers', function ($table) {
            $table->drop_foreign('teachers_schoolId_foreign');
        });

        Schema::table('smsTransactions', function ($table) {
            $table->drop_foreign('smstransactions_userId_foreign');
        });

        Schema::table('appSmsTransactions', function ($table) {
            $table->drop_foreign('appsmstransactions_userId_foreign');
        });

        Schema::table('smsCredits', function ($table) {
            $table->drop_foreign('smsCredits_schoolId_foreign');
        });

        Schema::table('transactions', function ($table) {
            $table->drop_foreign('transactions_schoolId_foreign');
        });

        //drop tables

        Schema::drop('schools');
        Schema::drop('users');
        Schema::drop('roles');
        Schema::drop('role_user');
        Schema::drop('students');
        Schema::drop('teachers');
        Schema::drop('smsTransactions');
        Schema::drop('appSmsTransactions');
        Schema::drop('smsCredits');
        Schema::drop('transactions');

    }

}

