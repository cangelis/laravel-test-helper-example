<?php

class Create_Post_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up() {
        Schema::create("post", function($table) {
                    $table->increments('id');
                    $table->integer('user_id');
                    $table->string('title');
                    $table->text('content');
                    $table->timestamps();
                });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        Schema::drop("post");
    }

}