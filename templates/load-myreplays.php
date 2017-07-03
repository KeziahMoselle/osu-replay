<?php
session_start();

require '../libs/db.php';

$private = $_POST['private'];
$private = "private";

$replays = $db->prepare('SELECT * FROM replays WHERE uploader = ? AND visibility = ? ORDER BY id DESC');
$replays->execute(array($_SESSION['username'],$private));

?>


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
