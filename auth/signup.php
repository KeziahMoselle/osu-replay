<?php
session_start();

if (isset($_POST['signup']))
{ //Si on soumet le formulaire
    if (isset($_POST['username'], $_POST['password'], $_POST['c_password']))
    { //Si tous les champs sont remplis

        require 'libs/db.php';
        $username = htmlspecialchars($_POST['username']);
        $password = sha1($_POST['password']);
        $c_password = sha1($_POST['c_password']);

        if (strlen($username) < 16)
        { //Longueur du pseudo valide

            $check_username = $db->prepare('SELECT * FROM users WHERE username = ?');
            $check_username->execute(array($username));

            if (!$check_username->rowCount())
            { //Si le pseudo n'existe pas dans la bdd

                $curl = curl_init();
                $url = "https://osu.ppy.sh/u/$username";
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($curl);

                preg_match("^\/\/a\.ppy\.sh\/(\d*)_(\d*)\.jpg^", $result, $matches);
                curl_close($curl);

                $avatar_link = $matches[0];

                if ($password == $c_password)
                { //Les mots de passe sont identiques
                    $insert = $db->prepare('INSERT INTO users(username,password,avatar_link) VALUES(?,?,?)');
                    $insert->execute(array($username,$password,$avatar_link));

                    $is_signup = $db->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
                    $is_signup->execute(array($username, $password));

                    if ($is_signup->rowCount())
                    { //Si l'utilisateur a bien été enregistré
                        $user = $is_signup->fetch();
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['token'] = mt_rand();
                        $_SESSION['auth'] = 1;
                        header('Location: ../index.php');
                        die();
                    }
                    else
                    {
                        $notif = "Erreur";
                    }
                }
                else
                { //Les mots de passe ne sont pas identiques
                    $notif = "Password does not match.";
                }
            }
            else
            { //Si le pseudo existe
                $notif = "Username already used.";
            }
        }
        else
        { //Le pseudo est trop long
            $notif = "Username is too long. (16 characters)";
        }
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>osu!replay - Sign up</title>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/assets/css/materialize.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/assets/css/style.css"  media="screen,projection"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>

        <?php require 'templates/header.php'; ?>


        <main>

            <div class="row">

                <div class="col s12 m10 offset-m1 l6 offset-l3">

                    <?php if(isset($notif)): ?>
                        <div class="card-panel white center-align">
                            <span class="black-text"><?= $notif ?></span>
                        </div>
                    <?php else: ?>
                        <div class="card-panel white center-align">
                            <span class="black-text">Enter your real osu!username please. However you can't upload replays.</span>
                        </div>
                    <?php endif ?>

                    <div class="card white">
                        <div class="card-content">
                            <h1 class="center-align">Sign up</h1>
                            <form class="row" action="signup.php" method="post">
                                <div class="input-field col s12 m6 offset-m3">
                                  <input name="username" id="username" type="text" placeholder="Username" required>
                                  <label for="username">osu! username</label>
                                </div>
                                <div class="input-field col s12 m6 offset-m3">
                                  <input name="password" id="password" type="password" placeholder="********" required>
                                  <label for="password">Password</label>
                                </div>
                                <div class="input-field col s12 m6 offset-m3">
                                  <input name="c_password" id="password" type="password" placeholder="********" required>
                                  <label for="password">Confirm Password</label>
                                </div>
                        </div>
                        <div class="card-action center">
                            <button name="signup" type="submit" class="btn waves-effect waves deep-purple accent-3">Sign up</button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>

        </main>

        <?php require 'templates/footer.php'; ?>
    </body>
</html>
