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
        <nav class="subnav">
            <div class="nav-wrapper grey darken-3">
              <form id="filters">
                <p class="center">
                  <input name="private" type="checkbox" class="filled-in" id="filled-in-box"/>
                  <label for="filled-in-box">Private</label>
                </p>
                <p id="results"></p>
              </form>
            </div>
        </nav>

        <main>

            <div id="view" class="row">

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
                                  <span class="card-title truncate"><?=$replay["title"]?><br/><?=$replay["artist"]?> by <?=$replay["creator"]?></span>
                                  <div class="btn-floating halfway-fab waves-effect waves-light deep-purple accent-2 list-fab">
                                    <i class="material-icons">more_vert</i>
                                  </div>
                                  <div class="list-menu">
                                    <ul>
                                      <a href="libs/download.php?id=<?=$replay['id']?>">
                                        <li>Download</li>
                                      </a>
                                      <a href="edit.php?id=<?=$replay['id']?>&token=<?=$_SESSION['token']?>">
                                        <li>Edit</li>
                                      </a>
                                      <a href="libs/delete_replay.php?id=<?=$replay['id']?>&token=<?=$_SESSION['token']?>">
                                        <li>Delete</li>
                                      </a>
                                    </ul>
                                  </div>

                              </div>
                              <div class="card-content center-align <?php if($replay['visibility'] == "private"){echo "private";}?>">
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
                <?php endif; ?>

            </div>

        </main>

        <?php require 'templates/footer.php'; ?>
        <script type="text/javascript">
          $(document).ready(function(){
            function values()
            {
              var private = $("#filters").serialize();
              console.log(private);

              $("#view").load("templates/load-myreplays.php", {
                private: private
              });
            }

            $("input").on("click", values);
          });
        </script>
    </body>
</html>
