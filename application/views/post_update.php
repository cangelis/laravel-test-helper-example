<!doctype html>
<html>
    <head>
        <title>Laravel Controller Testing</title>
    </head>
    <body>
        <div>
            <p style="text-align: right"><a href="/auth/logout">Logout</a></p>
            <?php
            if (Session::get('new_post', false) == true):
                ?>
                <p style="color: green">Post added successfully</p>
                <?php
            endif;
            $validation = unserialize(Session::get('validation'));
            if (!empty($validation)):
                foreach ($validation->getErrors() as $error):
                    ?>
                    <p style="color: red">* <?= $error ?></p>
                    <?php
                endforeach;
            endif;
            ?>
            <form action="" method="POST">
                <div>Title: <input type="text" value="<?= $post->title ?>" name="title"/></div>
                <div><p>Content:</p>
                    <textarea name="content" rows="10" cols="50"><?= $post->content ?></textarea>
                </div>
                <input type="hidden" value="<?= $post->id ?>">
                <div>
                    <input type="submit" value="Update!"/>
                </div>
            </form>
        </div>
    </body>
</html>
