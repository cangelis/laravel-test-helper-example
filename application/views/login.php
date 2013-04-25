<!doctype html>
<html>
    <head>
        <title>Laravel Controller Testing</title>
    </head>
    <body>
        <div>
            <?php
            $validation = unserialize(Session::get('validation'));
            if (!empty($validation)) {
                foreach ($validation->getErrors() as $error) {
                    ?>
                    <p style="color: red">* <?= $error ?></p>
                    <?
                }
            }
            ?>
            <form action="" method="POST">
                <div>Email: <input type="text" name="email"></div>
                <div>Password: <input type="password" name="password"></div>
                <div><input type="submit" value="Login!"></div>
            </form>
        </div>
    </body>
</html>