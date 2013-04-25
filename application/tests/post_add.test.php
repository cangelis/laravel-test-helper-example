<?php

use \TestHelper\TestHelper as TestHelper;

class PostAddTest extends PHPUnit_Framework_TestCase {

    public $user_id;

    public function setUp() {
        Session::start('database');
        Session::flush();
        /*
         * Insert test data
         */
        $user = new User;
        $user->email = 'testing@laravel.com';
        $user->password = '12345';
        $user->save();
        $this->user_id = $user->id;
    }

    public function tearDown() {
        /*
         * Delete test data
         */
        User::find($this->user_id)->delete();
    }

    public function testEmptyInputs() {
        // login user
        Session::put("user_id", $this->user_id);
        $test_result = TestHelper::runControllerTest("post", "new", "POST");
        $validation = unserialize(Session::get('validation'));
        $this->assertEquals(302, $test_result->statusCode);
        $this->assertEquals("post", $test_result->redirect_params[0]);
        $this->assertEquals("new", $test_result->redirect_params[1]);
        $this->assertFalse($validation->isSuccess());
        $this->assertTrue($validation->has('content', 'required'));
        $this->assertTrue($validation->has('title', 'required'));
        $this->assertFalse($validation->has('auth', 'is_logged_in'));
    }

    public function testEmptyTitleInput() {
        // login user
        Session::put("user_id", $this->user_id);
        $test_result = TestHelper::runControllerTest("post", "new", "POST", array('content' => 'test content'));
        $validation = unserialize(Session::get('validation'));

        $this->assertEquals(302, $test_result->statusCode);
        $this->assertEquals("post", $test_result->redirect_params[0]);
        $this->assertEquals("new", $test_result->redirect_params[1]);
        $this->assertFalse($validation->isSuccess());
        $this->assertFalse($validation->has('content', 'required'));
        $this->assertTrue($validation->has('title', 'required'));
        $this->assertFalse($validation->has('auth', 'is_logged_in'));
    }

    public function testEmptyContentInput() {
        // login user
        Session::put("user_id", $this->user_id);
        $test_result = TestHelper::runControllerTest("post", "new", "POST", array('title' => 'test title'));
        $validation = unserialize(Session::get('validation'));

        $this->assertEquals(302, $test_result->statusCode);
        $this->assertEquals("post", $test_result->redirect_params[0]);
        $this->assertEquals("new", $test_result->redirect_params[1]);
        $this->assertFalse($validation->isSuccess());
        $this->assertTrue($validation->has('content', 'required'));
        $this->assertFalse($validation->has('title', 'required'));
        $this->assertFalse($validation->has('auth', 'is_logged_in'));
    }

    public function testNotLoggedInUser() {
        Session::flush();
        $test_result = TestHelper::runControllerTest("post", "new", "POST", array('title' => 'test title', 'content' => 'test content'));
        $validation = unserialize(Session::get('validation'));

        $this->assertEquals(302, $test_result->statusCode);
        $this->assertEquals("post", $test_result->redirect_params[0]);
        $this->assertEquals("new", $test_result->redirect_params[1]);
        $this->assertFalse($validation->isSuccess());
        $this->assertFalse($validation->has('content', 'required'));
        $this->assertFalse($validation->has('title', 'required'));
        $this->assertTrue($validation->has('login', 'is_logged_in'));
    }

    public function testSuccessfulAdd() {
        $test_data = array('title' => 'test title', 'content' => 'test content');
        // login user
        Session::put("user_id", $this->user_id);
        $test_result = TestHelper::runControllerTest("post", "new", "POST", $test_data);
        $validation = unserialize(Session::get('validation'));

        $this->assertFalse($validation);
        $this->assertEquals(302, $test_result->statusCode);
        $this->assertEquals("post", $test_result->redirect_params[0]);
        $this->assertEquals("update", $test_result->redirect_params[1]);
        $post = Post::find($test_result->redirect_params[2]);
        $this->assertNotNull($post);

        //goto post/update/{id}
        $test2 = TestHelper::runControllerTest($test_result->redirect_params[0], $test_result->redirect_params[1], "GET", null, array($test_result->redirect_params[2]));
        $this->assertTrue(Session::get('new_post'));
        $this->assertEquals($test_data['title'], $test2->data['post']->title);
        $this->assertEquals($test_data['content'], $test2->data['post']->content);
        $this->assertEquals($this->user_id, $test2->data['post']->user_id);
        $post->delete();
    }

}

?>
