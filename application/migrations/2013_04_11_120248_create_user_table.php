<?php

class Create_User_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up() {
        Schema::create("user", function ($table) {
                    $table->increments('id');
                    $table->string('email');
                    $table->string('password');
                });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        Schema::drop('user');
    }

}