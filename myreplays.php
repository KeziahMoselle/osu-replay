<?php
session_start();

// FETCH REPLAY LIST
require 'libs/db.php';
$replays = $db->prepare('SELECT * FROM replays WHERE uploader = ?');
$replays->execute(array($_SESSION['username']));

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>osu!replay - My replays</title>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/assets/css/materialize.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/assets/css/style.css"  media="screen,projection"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>

        <?php require 'templates/header.php'; ?>

        <nav>
            <div class="nav-wrapper grey darken-3">
                <div class="col s12 center-align">
                    <span class="white-text">
                        <?php if ($replays->rowCount() == 0): ?>
                            Any replay found.
                        <?php else: ?>
                            You have <?=$user['replays']?> replays.
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </nav>


        <main>

            <div class="row">
                <div class="col m10 offset-m1">

                    <?php if ($replays->rowCount() > 0): ?>
                        <?php while($replay = $replays->fetch()) { ?>
                            <div class="col s12 m6 l4">
                              <div class="card grey lighten-3">
                                <div class="card-image">
                                    <img src="https://assets.ppy.sh//beatmaps/<?=$replay["beatmapset_id"]?>/covers/card.jpg">
                                    <span class="card-title"><?=$replay["title"]?><br/><?=$replay["artist"]?></span>
                                    <a href="libs/delete_replay.php?id=<?=$replay['id']?>&fileName=<?=$replay['replay_url']?>&token=<?=$_SESSION['token']?>" class="btn-floating floating-left halfway-fab waves-effect waves-light red"><i class="material-icons">delete</i></a>
                                    <a href="uploads/<?=$replay['replay_url']?>" class="btn-floating halfway-fab waves-effect waves-light deep-purple accent-2"><i class="material-icons">play_for_work</i></a>
                                </div>
                                <div class="card-content center-align">
                                    <p>
                                        <?=$replay["player"]?> (#<?=$replay['player_rank']?>)
                                        <br/>
                                        [<?=$replay["version"]?>] (<?=$replay["difficultyrating"]?>*)
                                        <br/>
                                    </p>
                                </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php endif; ?>

                </div>
            </div>

        </main>

        <?php require 'templates/footer.php'; ?>
    </body>
</html>
