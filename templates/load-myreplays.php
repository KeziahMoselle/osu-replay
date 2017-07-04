<?php
session_start();

require '../libs/db.php';

$private = explode('=',$_POST['private']);
$private = $private[0];

if (strlen($private) > 0)
{
  $replays = $db->prepare('SELECT * FROM replays WHERE uploader = ? AND visibility = ? ORDER BY id DESC');
  $replays->execute(array($_SESSION['username'],$private));
}
else
{
  $replays = $db->prepare('SELECT * FROM replays WHERE uploader = ? ORDER BY id DESC');
  $replays->execute(array($_SESSION['username']));
}


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
