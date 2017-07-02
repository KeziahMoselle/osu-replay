<?php
session_start();

require 'libs/db.php';

$replays = $db->prepare('SELECT * FROM replays WHERE visibility = ? ORDER BY id DESC');
$replays->execute(array("public"));

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>osu!replay - Explore</title>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/assets/css/materialize.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/assets/css/style.css"  media="screen,projection"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>

        <?php require 'templates/header.php'; ?>

        <div class="nav-content grey darken-3">
          <ul class="tabs tabs-transparent center">
              <li class="tab"><a id="btn-explore" href="#explore">Explore</a></li>
              <li class="tab"><a id="btn-leaderboard" href="#leaderboard">Leaderboard</a></li>
          </ul>
        </div>

        <nav class="hide">
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
              <div class="col m10 offset-m1">
                <div id="explore" class="col s12">
                  <?php while($replay = $replays->fetch()): ?>
                      <div class="col s12 m6 l4">
                        <div class="card grey lighten-3">
                            <div class="card-image">
                              <div class="chip">
                                <?=$replay['dl_count']?>
                              </div>
                              <img src="https://assets.ppy.sh//beatmaps/<?=$replay["beatmapset_id"]?>/covers/card.jpg">
                              <span class="card-title truncate"><?=$replay["title"]?><br/><?=$replay["artist"]?> by <?=$replay["creator"]?></span>
                              <a href="libs/download.php?id=<?=$replay['id']?>" class="btn-floating halfway-fab waves-effect waves-light deep-purple accent-2"><i class="material-icons">play_for_work</i></a>
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

                <div id="leaderboard" class="col s12">

                </div>
              </div>
            </div>

        </main>

        <?php require 'templates/footer.php'; ?>
        <script type="text/javascript">
          $(document).ready(function(){
            // Explore
            $("#btn-explore").click(function(){
              $("#explore").load("templates/explore.php");
            });

            $("#btn-leaderboard").click(function(){
              $("#leaderboard").load("templates/leaderboard.php");
            });

          });
        </script>
    </body>
</html>
