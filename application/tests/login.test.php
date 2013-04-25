<?php

use \TestHelper\TestHelper as TestHelper;

class LoginTest extends PHPUnit_Framework_TestCase {

    public $user_id;

    public function setUp() {
        Session::start('database');
        Session::flush();
        /*
         * Insert test user
         */
        $user = new User;
        $user->email = 'testing@laravel.com';
        $user->password = '12345';
        $user->save();
        $this->user_id = $user->id;
    }

    public function tearDown() {
        /*
         * Delete test user
         */
        User::find($this->user_id)->delete();
    }

    public function testEmptyInputParameters() {

        $test_result = TestHelper::runControllerTest("auth", "login", "POST");
        $this->assertEquals(302, $test_result->statusCode);
        $this->assertEquals("auth", $test_result->redirect_params[0]);
        $this->assertEquals("login", $test_result->redirect_params[1]);

        $test2 = TestHelper::runControllerTest("auth", "login", "GET");
        $validation = unserialize(Session::get('validation'));
        $this->assertNotNull($validation);
        $this->assertFalse($validation->isSuccess());
        $this->assertTrue($validation->has('email', 'email'));
        $this->assertTrue($validation->has('email', 'required'));
        $this->assertTrue($validation->has('password', 'required'));
        $this->assertTrue($validation->has('email', 'is_valid'));
        $this->assertEquals(200, $test2->statusCode);
        $this->assertEquals("login", $test2->view);
    }

    public function testEmptyEmailInput() {
        $inputs = array('password' => '1234');
        $test_result = TestHelper::runControllerTest("auth", "login", "POST", $inputs);
        $this->assertEquals(302, $test_result->statusCode);
        $this->assertEquals("auth", $test_result->redirect_params[0]);
        $this->assertEquals("login", $test_result->redirect_params[1]);

        $test2 = TestHelper::runControllerTest("auth", "login", "GET");
        $validation = unserialize(Session::get('validation'));
        $this->assertNotNull($validation);
        $this->assertFalse($validation->isSuccess());
        $this->assertTrue($validation->has('email', 'email'));
        $this->assertTrue($validation->has('email', 'required'));
        $this->assertFalse($validation->has('password', 'required'));
        $this->assertTrue($validation->has('email', 'is_valid'));
        $this->assertEquals(200, $test2->statusCode);
        $this->assertEquals("login", $test2->view);
    }

    public function testInvalidEmailInput() {
        $test_data = array('email' => 'foo','password' => '1234');
        $test_result = TestHelper::runControllerTest("auth", "login", "POST", $test_data);
        $this->assertEquals(302, $test_result->statusCode);
        $this->assertEquals("auth", $test_result->redirect_params[0]);
        $this->assertEquals("login", $test_result->redirect_params[1]);

        $test2 = TestHelper::runControllerTest("auth", "login", "GET");
        $validation = unserialize(Session::get('validation'));
        $this->assertNotNull($validation);
        $this->assertFalse($validation->isSuccess());
        $this->assertTrue($validation->has('email', 'email'));
        $this->assertFalse($validation->has('email', 'required'));
        $this->assertFalse($validation->has('password', 'required'));
        $this->assertTrue($validation->has('email', 'is_valid'));
        $this->assertEquals(200, $test2->statusCode);
        $this->assertEquals("login", $test2->view);
    }

    public function testGetLoginView() {
        $test_result = TestHelper::runControllerTest("auth", "login", "GET");

        $this->assertEquals("login", $test_result->view);
        $this->assertEquals(200, $test_result->statusCode);
    }

}

?>
