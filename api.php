<?php
session_start();

require 'libs/osu_api.php';

if (isset($_POST['username']))
{
    $u = htmlspecialchars($_POST['username']);

    $user = get_user($key, $u, 0);
    $user_best = get_user_best($key, $u, 0);
}

if (isset($_POST['beatmap_id']))
{
    $b = htmlspecialchars($_POST['beatmap_id']);

    $beatmap = get_beatmaps($key, $b);
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>osu!replay - API TEST</title>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/assets/css/materialize.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/assets/css/style.css"  media="screen,projection"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>

        <?php require 'templates/header.php'; ?>


        <main>

            <div class="row">

                <div class="col s12 m6 offset-m3">

                    <?php if(isset($user, $user_best)): ?>
                        <div class="card white">
                            <div class="card-content">
                                <h1 class="center-align">API results</h1>
                                <hr/>
                                <h2 class="center-align">User stats</h2>
                                    <p class="center-align">Here are the results for : <b><?= $user[0]['username'] ?></b></p>
                                    <ul class="center">
                                        <li>Accuracy : <?= $user[0]['accuracy'] ?>%</li>
                                        <li>Playcount : <?= $user[0]['playcount'] ?></li>
                                        <li>Level : <?= $user[0]['level'] ?></li>
                                        <li>This player is : <?= $user[0]['country'] ?></li>
                                        <li>His rank : #<?= $user[0]['pp_rank'] ?></li>
                                        <li>His country rank : #<?= $user[0]['pp_country_rank'] ?></li>
                                    </ul>
                                    <h2 class="center-align">Best performance</h2>
                                    <ul class="center">
                                        <li><?= $user_best[0]['pp'] ?> pp</li>
                                        <li><?= $user_best[1]['pp'] ?> pp</li>
                                        <li><?= $user_best[2]['pp'] ?> pp</li>
                                        <li><?= $user_best[3]['pp'] ?> pp</li>
                                        <li><?= $user_best[4]['pp'] ?> pp</li>
                                        <li><?= $user_best[5]['pp'] ?> pp</li>
                                        <li><?= $user_best[6]['pp'] ?> pp</li>
                                        <li><?= $user_best[7]['pp'] ?> pp</li>
                                        <li><?= $user_best[8]['pp'] ?> pp</li>
                                        <li><?= $user_best[9]['pp'] ?> pp</li>
                                    </ul>
                                    <a class="btn waves-effect waves pink center" href="api.php">Return</a>
                            </div>
                        </div>
                    <?php elseif (isset($_POST['beatmap_id'])): ?>
                        <div class="card white">
                            <div class="card-content">
                                <h1 class="center-align">API results</h1>
                                <hr/>
                                <h2 class="center-align">Beatmap</h2>
                                    <p class="center-align"><b><?= $beatmap[0][artist].' - '.$beatmap[0][title].' ['.$beatmap[0][version].']'.' By '.$beatmap[0][creator] ?></b></p>
                                    <ul class="center">
                                        <li>Stars : <?= $beatmap[0][difficultyrating] ?></li>
                                        <li>Max combo : <?= $beatmap[0][max_combo] ?></li>
                                    </ul>
                                    <a class="btn waves-effect waves pink center" href="api.php">Return</a>
                            </div>
                        </div>
                    <?php else: ?>
                    <div class="card white">
                        <div class="card-content">
                            <h1 class="center-align">API test</h1>
                            <form class="row" action="api.php" method="post">
                                <div class="input-field col s12 m6 offset-m3">
                                  <input name="username" id="username" type="text" required>
                                  <label for="username">osu! username</label>
                                </div>
                        </div>
                        <div class="card-action center">
                            <button type="submit" class="btn waves-effect waves pink">Test!</button>
                            </form>
                        </div>
                    </div>
                    <div class="card white">
                        <div class="card-content">
                            <form class="row" action="api.php" method="post">
                                <div class="input-field col s12 m6 offset-m3">
                                  <input name="beatmap_id" id="username" type="text" required>
                                  <label for="username">Beatmap ID</label>
                                </div>
                        </div>
                        <div class="card-action center">
                            <button type="submit" class="btn waves-effect waves pink">Test!</button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>

            </div>

        </main>

        <?php require 'templates/footer.php'; ?>
    </body>
</html>
