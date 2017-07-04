<?php
session_start();
$title = "Favorites";
$page = "favorites.php";

require 'libs/db.php';

$user_id = $_SESSION['id'];

$queryFav = $db->prepare('SELECT * FROM favorites WHERE user = ?');
$queryFav->execute(array($user_id));

$favCount = $queryFav->rowCount();



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

        <nav class="subnav">
            <div class="nav-wrapper grey darken-3">
                <div class="col s12 center-align">
                    <span class="white-text">
                      You have <b><?=$favCount?></b> favorites.
                    </span>
                </div>
            </div>
        </nav>

        <main>

            <div class="row">
              <div class="col m10 offset-m1">

              <?php while($fav = $queryFav->fetch()): ?>
                <?php
                  $queryReplay = $db->prepare('SELECT * FROM replays WHERE id = ?');
                  $queryReplay->execute(array($fav['replay']));
                  $replay = $queryReplay->fetch();
                ?>
                <div class="col s12 m6 l4">
                  <div class="card grey lighten-3">
                      <div class="card-image">
                        <div class="chip">
                          <?=$replay['dl_count']?>
                        </div>
                        <img src="https://assets.ppy.sh//beatmaps/<?=$replay["beatmapset_id"]?>/covers/card.jpg">
                        <span class="card-title truncate"><?=$replay["title"]?><br/><?=$replay["artist"]?> by <?=$replay["creator"]?></span>
                          <div class="btn-floating halfway-fab waves-effect waves-light deep-purple accent-2 list-fab"><i class="material-icons">more_vert</i></div>
                          <div class="list-menu">
                            <ul>
                              <a href="libs/download.php?id=<?=$replay['id']?>">
                                <li>Download</li>
                              </a>
                              <?php if (strlen($replay['youtube_url']) != 0): ?>
                                <a href="<?=$replay['youtube_url']?>">
                                  <li>View</li>
                                </a>
                              <?php endif; ?>
                              <a href="libs/favorite.php?id=<?=$replay['id']?>&token=<?=$_SESSION['token']?>&ref=<?=$page?>">
                                <li>Remove from favorites</li>
                              </a>
                            </ul>
                          </div>
                      </div>
                      <div class="card-content center-align">
                        <p>
                          Played by <?=$replay["player"]?> (#<?=$replay['player_rank']?>)
                          <br/>
                          On [<?=$replay["version"]?>] (<?=$replay["difficultyrating"]?>*)
                          <br/>
                        </p>
                      </div>
                    </div>
                </div>
              <?php endwhile; ?>

              </div>
            </div>

        </main>

        <?php require 'templates/footer.php'; ?>
    </body>
</html>
