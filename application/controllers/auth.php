<?php

class Auth_Controller extends Base_Controller {

    public $restful = true;

    public function get_login() {
        if (Laravel\Session::get("user_id") != null)
            return Redirect::to('post/new');
        return View::make("login");
    }

    public function post_login() {
        Bundle::start("simplevalidator");

        $input = Input::all();
        $user_info = null;
        $rules = array(
            'email' => array(
                'required',
                'email',
                'is_valid(:password)' => function($input, $param) use (&$user_info) {
                    $user_info = User::where_email_and_password($input, $param)->first();
                    if ($user_info == null)
                        return false;
                    return true;
                }
            ),
            'password' => array('required')
        );
        $messages = array(
            'is_valid' => 'Invalid :attribute or :input_param'
        );
        $naming = array(
            'email' => 'E-mail',
            'password' => 'Password'
        );
        $validation = MyValidator::validate($input, $rules, $naming);
        $validation->customErrors($messages);
        if ($validation->isSuccess() == false) {
            return Redirect::to('auth/login')->with('validation', serialize($validation));
        } else {
            Session::put("user_id", $user_info->id);
            return Redirect::to('post/new');
        }
    }

    public function get_logout() {
        Session::flush();
        return Redirect::to('auth/login');
    }

}

?>
