<!doctype html>
<html>
    <head>
        <title>Laravel Controller Testing</title>
    </head>
    <body>
        <div>
            <p style="text-align: right"><a href="/auth/logout">Logout</a></p>
            <div>
                <?php
                $validation = unserialize(Session::get('validation'));
                if (!empty($validation)) {
                    foreach ($validation->getErrors() as $error) {
                        ?>
                        <p style="color: red">* <?= $error ?></p>
                        <?php
                    }
                }
                ?>
            </div>
            <form action="" method="POST">
                <div>Title: <input value="<?= Input::old('title') ?>" type="text" name="title"/></div>
                <div><p>Content:</p>
                    <textarea name="content" rows="10" cols="50"><?= Input::old('content') ?></textarea>
                </div>
                <div>
                    <input type="submit" value="Add!"/>
                </div>
            </form>
        </div>
    </body>
</html>
