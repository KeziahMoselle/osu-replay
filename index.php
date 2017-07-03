<?php
session_start();
$title = "Index";
$page = "index.php";

require 'libs/db.php';

$replays = $db->prepare('SELECT * FROM replays WHERE visibility = ? ORDER BY id DESC');
$replays->execute(array("public"));

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

        <div class="nav-content grey darken-3">
          <ul class="tabs tabs-transparent center">
              <li class="tab"><a id="btn-explore" href="#explore">Explore</a></li>
              <li class="tab"><a id="btn-leaderboard" href="#leaderboard">Leaderboard</a></li>
          </ul>
        </div>

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
                              <?php if (strlen($replay['youtube_url']) != 0): ?>
                                <div class="btn-floating halfway-fab waves-effect waves-light deep-purple accent-2 list-fab"><i class="material-icons">more_vert</i></div>
                                  <div class="list-menu">
                                    <ul>
                                      <a href="libs/download.php?id=<?=$replay['id']?>">
                                        <li>Download</li>
                                      </a>
                                      <a href="<?=$replay['youtube_url']?>">
                                        <li>View</li>
                                      </a>
                                    </ul>
                                  </div>
                              <?php else: ?>
                                <a href="libs/download.php?id=<?=$replay['id']?>" class="btn-floating halfway-fab waves-effect waves-light deep-purple accent-2"><i class="material-icons">play_for_work</i></a>
                              <?php endif; ?>
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

                  <div class="center">
                    <div class="preloader-wrapper big active">
                      <div class="spinner-layer spinner-purple-only">
                        <div class="circle-clipper left">
                          <div class="circle"></div>
                        </div><div class="gap-patch">
                          <div class="circle"></div>
                        </div><div class="circle-clipper right">
                          <div class="circle"></div>
                        </div>
                      </div>
                    </div>
                  </div>

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

            // Leaderboard
            $("#btn-leaderboard").click(function(){
              $("#leaderboard").load("templates/leaderboard.php");
            });

          });
        </script>
    </body>
</html>
