<?php
session_start();
$title = "Index";
$page = "index.php";

require 'libs/db.php';

require 'libs/search.php';
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
            <form class="row" action="index.php" method="POST">
              <div class="input-field col s12 m6 offset-m3">
                <input name="search" id="search" type="search" placeholder="Type some key words (artist,title,difficulty name,creator)" required>
                <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                <i class="material-icons">close</i>
              </div>
            </form>
          </div>
        </nav>

        <main>

            <div class="row">
              <div class="col m10 offset-m1">

                <?php while($replay = $query->fetch()): ?>
                  <?php
                    $isfav = $db->prepare('SELECT * FROM favorites WHERE user = ? AND replay = ?');
                    $isfav->execute(array($_SESSION['id'],$replay['id']));
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
                                  <?php if (isset($_SESSION['auth'])): ?>
                                    <a href="libs/favorite.php?id=<?=$replay['id']?>&token=<?=$_SESSION['token']?>&ref=<?=$page?>">
                                    <?php if ($isfav->rowCount() == 0): ?>
                                      <li>Add to favorite</li>
                                    <?php else: ?>
                                      <li>Remove from favorites</li>
                                    <?php endif; ?>
                                    </a>
                                  <?php endif; ?>
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
        <script type="text/javascript">

          $("input").keyup(function(){

            $.post("libs/search.php",
            {
              search : $("input").val()
            },
            function(data)
            {
              console.log(JSON.parse(data));
            });

          });
        </script>
    </body>
</html>
