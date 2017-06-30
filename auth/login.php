<?php
session_start();

if (isset($_POST['login']))
{ //Si on soumet le formulaire
    if (isset($_POST['username'], $_POST['password']))
    { //Si les champs sont remplis

        require '../libs/db.php';
        $username = htmlspecialchars($_POST['username']);
        $password = sha1($_POST['password']);

        $is_user = $db->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
        $is_user->execute(array($username, $password));

        if ($is_user->rowCount())
        { //Si l'utilisateur existe
            $user = $is_user->fetch();
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['token'] = mt_rand();
            $_SESSION['auth'] = 1;
            header('Location: ../index.php');
            die();
        }
        else
        { //Si l'utilisateur n'existe pas
            $notif = "Username or password incorrect";
        }
    }
    else
    { //Si les champs ne sont pas remplis
        $notif = "No values";
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>osu!replay - Login</title>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/assets/css/materialize.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/assets/css/style.css"  media="screen,projection"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>

        <?php require '../templates/header.php'; ?>
        <nav>
            <div class="nav-wrapper grey darken-3">
                <div class="col s12 center-align">
                    <span class="white-text">
                      <?php if (isset($notif)): ?>
                        <?= $notif ?>
                      <?php endif; ?>
                    </span>
                </div>
            </div>
        </nav>


        <main>

            <div class="row">

                <div class="col s12 m10 offset-m1 l6 offset-l3">

                    <div class="card white">
                        <div class="card-content">
                            <h1 class="center-align">Login</h1>
                            <form class="row" action="login.php" method="post">
                                <div class="input-field col s12 m6 offset-m3">
                                  <input name="username" id="username" type="text" required>
                                  <label for="username">osu! username</label>
                                </div>
                                <div class="input-field col s12 m6 offset-m3">
                                  <input name="password" id="password" type="password" required>
                                  <label for="password">Password</label>
                                </div>
                        </div>
                        <div class="card-action center">
                            <button name="login" type="submit" class="btn waves-effect waves deep-purple accent-2">Login</button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>

        </main>

        <?php require '../templates/footer.php'; ?>
    </body>
</html>
