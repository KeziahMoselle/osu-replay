<?php
session_start();
$title = "My replays";
$page = "my-replays.php";

require 'libs/db.php';

$replays = $db->prepare('SELECT * FROM replays WHERE uploader = ? ORDER BY id DESC');
$replays->execute(array($_SESSION['username']));

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>osu!replay - <?=$title?></title>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/assets/css/materialize.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/assets/css/style.css"  media="screen,projection"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>

        <?php require 'templates/header.php'; ?>
        <nav>
            <div class="nav-wrapper grey darken-3">
              <form action="index.php" method="GET">
                <div class="input-field">
                  <input id="search" type="search" placeholder="Search isn't available for the moment." required>
                  <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                  <i class="material-icons">close</i>
                </div>
              </form>
            </div>
        </nav>

        <main>

            <div class="row">
              <div id="view" class="col s12">
                <?php if ($replays->rowCount() > 0): ?>
                    <div class="col m10 offset-m1">
                      <?php while($replay = $replays->fetch()): ?>
                          <div class="col s12 m6 l4">
                            <div class="card grey lighten-3">
                              <div class="card-image">
                                  <div class="chip">
                                    <?=$replay['dl_count']?>
                                  </div>
                                  <img src="https://assets.ppy.sh//beatmaps/<?=$replay["beatmapset_id"]?>/covers/card.jpg">
                                  <span class="card-title truncate"><?=$replay["title"]?><br/><?=$replay["artist"]?></span>
                                  <a href="edit.php?id=<?=$replay['id']?>&token=<?=$_SESSION['token']?>" class="btn-floating floating-left halfway-fab waves-effect waves-light grey"><i class="material-icons">mode_edit</i></a>
                                  <a href="libs/download.php?id=<?=$replay['id']?>" class="btn-floating halfway-fab waves-effect waves-light deep-purple accent-2"><i class="material-icons">play_for_work</i></a>
                              </div>
                              <div class="card-content center-align <?php if($replay['visibility'] == "private"){echo "private";}?>">
                                <span class="card-title">
                                  Played by <?=$replay["player"]?> (#<?=$replay['player_rank']?>)
                                  <br/>
                                  On [<?=$replay["version"]?>] (<?=$replay["difficultyrating"]?>*)
                                </span>
                              </div>
                            </div>
                          </div>
                      <?php endwhile; ?>
                      </div>
                <?php endif; ?>
              </div>

            </div>

        </main>

        <?php require 'templates/footer.php'; ?>
    </body>
</html>
