<?php

class Post_Controller extends Base_Controller {

    public $restful = true;

    public function get_new() {
        if (!Session::has('user_id'))
            return Redirect::to('auth/login');
        return View::make("post_new");
    }

    public function post_new() {
        $inputs = Input::all();
        $rules = array(
            'login' => array(
                'is_logged_in' => function() {
                    return Session::has("user_id");
                }
            ),
            'title' => array('required'),
            'content' => array('required')
        );
        $naming = array(
            'title' => 'Title',
            'content' => 'Post Content'
        );
        $validation = SimpleValidator\Validator::validate($inputs, $rules, $naming);
        $validation->customErrors(array(
            'login.is_logged_in' => 'You need to login in to create a new post'
        ));
        if ($validation->isSuccess()) {
            $post = new Post;
            $post->title = Input::get('title');
            $post->content = Input::get('content');
            $post->user_id = Session::get('user_id');
            $post->save();
            return Redirect::to('post/update/' . $post->id)->with('new_post', true);
        } else {
            return Redirect::to('post/new')->with('validation', serialize($validation))->with_input();
        }
    }

    public function get_update($id) {
        $post = Post::find($id);
        if ($post == null) {
            return Redirect::to('post/new');
        }
        return View::make("post_update")->with('post', $post);
    }
    
    public function post_update() {
        
    }

}

?>
