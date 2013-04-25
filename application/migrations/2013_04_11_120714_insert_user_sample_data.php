<?php

class Insert_User_Sample_Data {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up() {
        DB::table('user')->insert(array(
            'email' => 'test@laravel.com',
            'password' => '123456'
        ));
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        DB::table('user')->where('email', '=', 'test@laravel.com')->delete();
    }

}